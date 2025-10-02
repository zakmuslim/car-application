<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationPolicy 
{   public function viewAny(User $user){ return $user->role === 'consultant'; }
    public function view(User $user, LoanApplication $loan){ return $user->role === 'consultant'; }
    public function update(User $user, LoanApplication $loan){ return $user->role === 'consultant'; }
    public function delete(User $user, LoanApplication $loan){ return $user->role === 'consultant'; }
}
