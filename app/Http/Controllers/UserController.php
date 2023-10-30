<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|string',
            'email' => 'email|required|string',
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'image' => 'nullable|string'
        ]);

        if ($data['image']) {
            if (!str_contains($data['image'], 'images')) {
                $data['image'] = $this->saveImage($data['image']);

                if ($request->user()->image) {
                    $absolutePath = public_path($request->user()->image);
                    File::delete($absolutePath);
                }
            }
        }
        if ($request->user()->update($data)) {
            return [
                'nickname' => $request->user()->nickname,
                'email' => $request->user()->email,
                'image' => $request->user()->image ? URL::to($request->user()->image) : null,
                'first_name' => $request->user()->first_name,
                'last_name' => $request->user()->last_name,
            ];
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'password' => 'required'
        ]);

        if (password_verify($data['password'], $request->user()->password)) {
            $user = $request->user();
            $sessions = $user->sessions;
            foreach ($sessions as $session) {
                $session->solves()->delete();
            }
            $user->sessions()->delete();
            if ($user->delete()) {
                return true;
            } else {
                return response([
                    'error' => 'Something went wrong'
                ]);
            }
        } else {
            return response([
                'error' => 'Wrong password'
            ]);
        }
    }

    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out base64 out of the image URI
            $image = substr($image, strpos($image, ',') + 1);
            // Get the file extension
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'png', 'jpeg', 'gif'])) {
                throw new \Exception('Invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }
}
