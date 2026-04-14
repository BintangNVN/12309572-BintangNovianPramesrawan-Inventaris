<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    private string $role;

    // public function __construct(string $role = 'admin')
    // {
    //     $this->role = $role;
    // }

    public function collection()
    {
        // return User::where('role', $this->role)->get();
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->is_default_password
                ? $this->getDefaultPassword($user)
                : 'This account already edited the Password',
        ];
    }

    private function getDefaultPassword(User $user): string
    {
        return substr($user->email, 0, 4) . $user->id;
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Password'];
    }
}
