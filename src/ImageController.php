<?php

namespace Waxis\Core;

use Illuminate\Http\Request;

use Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;

class ImageController extends Controller
{
    public $folder = null;

    public $type = null;

    public $file = null;

    public $originalPath = null;

    public $cachePath = null;

    public function get($folder, $type, $file = null)
    {
        $this->folder = $folder;
        $this->type = $type;
        $this->file = $file;
        $this->originalPath = $originalPath = public_path() . '/uploads/images/' . $this->folder . '/';
        $this->cachePath = $cachePath = public_path() . '/uploads/images/' . $this->folder . '/cache/' . $type . '/';

        $renderPath = $this->cachePath;

        if (empty($file) || $file == 'default' || !file_exists($originalPath . $file)) {
            $file = $this->getDefault();
        }

        if (!file_exists($cachePath . $file)) {
            $image = Image::make($originalPath . $file);

            $descriptor = '\App\Descriptors\Image\\' . ucfirst($folder);
            $descriptor = new $descriptor;

            $params = $descriptor->types[$type];

            $width = $params['size'][0];
            $height = $params['size'][1];
            $mode = getValue($params['size'],2);

            switch ($mode) {
                case 'crop':
                    $image->fit($width, $height);
                    break;
                
                default:
                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    break;
            }

            $api = isset($params['api']) ? $params['api'] : null;

            if ($api !== null) {
                foreach ($api as $action => $options) {
                    switch ($action) {
                        default:
                            call_user_func_array(array($image, $action), $options);
                            break;
                    }
                }
            }

            if (!file_exists($cachePath)) {
                mkdir($cachePath, 0755, true);
            }

            $image->save($cachePath . $file);

            $renderPath = $cachePath;
        }

        return $this->render(file_get_contents($renderPath . $file));
    }

    public function getDefault()
    {
        $file = 'default';

        $extensions = ['jpg', 'jpeg', 'png'];

        $default = null;
        foreach ($extensions as $extension) {
            if (file_exists($this->originalPath . $file . '-' . $this->type . '.' . $extension)) {
                $default = $file . '-' . $this->type . '.' . $extension;
            }
            elseif (file_exists($this->originalPath . $file . '-' . $this->type . '.' . strtoupper($extension))) {
                $default = $file . '.' . strtoupper($extension);
            }
            elseif (file_exists($this->originalPath . $file . '.' . $extension)) {
                $default = $file . '.' . $extension;
            }
            elseif (file_exists($this->originalPath . $file . '.' . strtoupper($extension))) {
                $default = $file . '.' . strtoupper($extension);
            }

            if ($default !== null) {
                break;
            }
        }

        if ($default === null) {
            $file = $this->originalPath . $file . '.png';

            $canvas = Image::canvas(1,1);
            $canvas->save($file);

            $default = $file;
        }

        return $default;
    }

    public function render($content)
    {
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        // return http response
        return new IlluminateResponse($content, 200, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age='.(86400*360).', public',
            'Etag' => md5($content)
        ));
    }
}