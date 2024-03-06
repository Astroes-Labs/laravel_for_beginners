<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        $path = $this->storeAvatar($request->file('avatar'), $request->user());

        return redirect(route('profile.edit'))->with(['message' => 'Avatar is Updated']);
    }

    public function generate()
    {
        $filename = $this->generateFilename();
        $path = $this->storeGeneratedAvatar($filename);

        return redirect(route('profile.edit'))->with(['message' => 'Avatar is Updated']);
    }

    private function storeAvatar($avatarFile, $user)
    {
        $path = $avatarFile->store('avatar', 'public');

        if ($oldAvatar = $user->avatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        $user->update(['avatar' => $path]);

        return $path;
    }

    private function generateFilename()
    {
        return Str::random(25) . '.jpg';
    }

    private function storeGeneratedAvatar($filename)
    {
        $result = OpenAI::images()->create([
            "prompt" => "create single avatar for user",
            "n" => 1,
            "size" => "256x256",
        ]);

        $content = file_get_contents($result->data[0]->url);
        $path = Storage::disk('public')->put("avatar/$filename", $content);

        auth()->user()->update(['avatar' => "avatar/$filename"]);

        return $path;
    }
}

