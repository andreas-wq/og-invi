<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InviteController extends Controller
{
    public function index(): View
    {
        $defaultTemplate = "Assalamu'alaikum Bapak/Ibu/Saudara/i {nama},\n\nTanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir di acara pernikahan kami.\n\nBerikut link undangan digital kami, mohon buka untuk info lengkap acara:\n{link}\n\nMerupakan suatu kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.\n\nTerima kasih banyak 🙏";

        return view('admin.invite', [
            'title' => 'Kirim Undangan',
            'invitationUrl' => url('/'),
            'defaultTemplate' => $defaultTemplate,
        ]);
    }
}
