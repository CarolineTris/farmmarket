<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class AdminFarmers extends Component
{
    use WithPagination;

    public $search = '';
    public $verificationStatus = 'pending';
    public $selectedFarmer = null;
    public $verificationNotes = '';
    public $rejectionReason = '';
    public $showRejectionForm = false;
    
    // Verification checklist
    public $verificationData = [
        'id_verified' => false,
        'document_clear' => false,
        'location_verified' => false,
        'farm_verified' => false,
        'phone_verified' => false,
    ];
    
    public $stats = [];

    protected $queryString = ['search', 'verificationStatus'];

    public function mount()
    {
        $this->loadStats();
        // Automatically select first pending farmer
        $this->selectFirstPendingFarmer();
    }

    public function loadStats()
    {
        $this->stats = [
            'pending' => User::pendingFarmers()->count(),
            'verified' => User::verifiedFarmers()->count(),
            'rejected' => User::farmers()->where('verification_status', 'rejected')->count(),
            'total' => User::farmers()->count(),
        ];
    }

    public function selectFirstPendingFarmer()
    {
        $farmer = User::pendingFarmers()->first();
        if ($farmer) {
            $this->selectFarmer($farmer->id);
        }
    }

    public function selectFarmer($farmerId)
    {
        $this->selectedFarmer = User::find($farmerId);
        $this->verificationNotes = $this->selectedFarmer->verification_notes ?? '';
        $this->showRejectionForm = false;
        
        // Load existing verification data
        if ($this->selectedFarmer->verification_data) {
            $data = json_decode($this->selectedFarmer->verification_data, true);
            $this->verificationData = array_merge($this->verificationData, $data);
        }
        
        // Reset verification checklist for new farmer
        if ($this->selectedFarmer->verification_status !== 'pending') {
            $this->verificationData = [
                'id_verified' => false,
                'document_clear' => false,
                'location_verified' => false,
                'farm_verified' => false,
                'phone_verified' => false,
            ];
        }
    }

    public function verifyFarmer()
    {
        // Validate at least some checks are done
        if (!array_filter($this->verificationData)) {
            session()->flash('error', 'Please complete at least one verification check.');
            return;
        }

        $this->selectedFarmer->update([
            'verification_status' => 'verified',
            'verification_notes' => $this->verificationNotes,
            'verification_data' => json_encode($this->verificationData),
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Send verification email (optional)
        // Mail::to($this->selectedFarmer->email)->send(new FarmerVerifiedMail($this->selectedFarmer));

        session()->flash('success', 'Farmer verified successfully!');
        $this->loadStats();
        
        // Move to next pending farmer
        $this->selectNextPendingFarmer();
    }

    public function rejectFarmer()
    {
        $this->validate([
            'rejectionReason' => 'required|min:10|max:500',
        ]);

        $this->selectedFarmer->update([
            'verification_status' => 'rejected',
            'verification_notes' => "REJECTED: " . $this->rejectionReason,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Send rejection email (optional)
        // Mail::to($this->selectedFarmer->email)->send(new FarmerRejectedMail($this->selectedFarmer, $this->rejectionReason));

        session()->flash('error', 'Farmer application rejected.');
        $this->loadStats();
        $this->rejectionReason = '';
        $this->showRejectionForm = false;
        
        // Move to next pending farmer
        $this->selectNextPendingFarmer();
    }

    public function requestMoreInfo()
    {
        $this->selectedFarmer->update([
            'verification_notes' => "MORE INFO REQUESTED: " . $this->verificationNotes,
        ]);

        // Send email requesting more information
        // Mail::to($this->selectedFarmer->email)->send(new FarmerMoreInfoRequest($this->selectedFarmer));

        session()->flash('info', 'Request for more information sent to farmer.');
    }

    private function selectNextPendingFarmer()
    {
        $nextFarmer = User::pendingFarmers()
            ->where('id', '!=', $this->selectedFarmer->id)
            ->first();
        
        if ($nextFarmer) {
            $this->selectFarmer($nextFarmer->id);
        } else {
            $this->selectedFarmer = null;
        }
    }

    public function toggleRejectionForm()
    {
        $this->showRejectionForm = !$this->showRejectionForm;
    }

    public function render()
    {
        $farmers = User::farmers()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('id_number', 'like', '%' . $this->search . '%')
                      ->orWhere('farm_location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->verificationStatus, function ($query) {
                $query->where('verification_status', $this->verificationStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin-farmers', [
            'farmers' => $farmers,
        ])->layout('layouts.admin');
    }
}