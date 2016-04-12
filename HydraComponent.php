<?php

/**
 * @link http://zabachok.net/
 * @copyright Copyright (c) 2015 Daniil Romanov
 *
 */

namespace zabachok\hydra;

use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;


//
//1. saveFile(source, filePath, toCompress)
//1. hasFile(filePath)
//1. fileInfo(filePath, public=false)
//1. removeFile(filePath)
//1. findCacheFiles(filePath)
//1. getCachePath(filePath, resolution)
//1. clearFileCache(filePath)
//1. clearCache()
//1. findFiles(dirPath)
//1. createDirectory(dirPath)
//1. removeDirectory(dirPath)

/**
 *
 * @author Daniil Romanov <zabachok@zabachok.net>
 * @since 1.0
 */
class HydraComponent extends \yii\base\Component
{

    /**
     * @var string The root directory of the files.
     */
    public $filesPath;

    /**
     * @var string The root directory of the resized images.
     */
    public $cachePath;

    /**
     * @var string Root public url of the files.
     */
    public $filesUrl = '/data/files';

    /**
     * @var string Root public url of the resized images.
     */
    public $cacheUrl = '/data/cache';

    /**
     * @var array Allowed extensions of images.
     */
    protected $imagesExtensions = ['png', 'gif', 'jpg', 'jpeg'];

    public $imagesContentType = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
    ];

    /**
     * @var array Forbidden extensions.
     */
    protected $forbiddenExtensions = [
        'php',
        'php3',
        'php4',
        'php5',
        'html',
        'js',
        'css',
        'sh',
        'pl',
        'htm',
        'shtm',
        'shtml',
    ];

    /**
     * @var array Allowed resolutions.
     *
     * You can use pattern or name.
     *
     * By pattern:
     *
     *     //in config
     *     'resolutions' => [
     *         '100x100p'
     *     ],
     *     //in your code
     *     Yii::$app->hydra->getCacheUrl('image.jpg', '100x100p');
     *
     *
     * By name:
     *
     *     //in config
     *     'resolutions' => [
     *         'newsPreview' => '100x100p',
     *     ],
     *     //in your code
     *     Yii::$app->hydra->getCacheUrl('image.jpg', 'newsPreview');//It will use 100x100p
     *
     * @see getCacheUrl()
     */
    public $resolutions = [
        'preview'  => '100x100i',
        'newslist' => '150x150i',
        'newsview' => '300x300i',
        '130x120p',
    ];

    /**
     * When forceSave is false and file exists, will found new file name.
     * @var boolean Save file if it exists.
     */
    public $forceSave = true;

    public function __construct()
    {
        $this->filesPath = Yii::$app->basePath . '/web' . $this->filesUrl;
        $this->cachePath = Yii::$app->basePath . '/web' . $this->cacheUrl;
    }

    public function init()
    {
        echo $this->filesPath;
    }

    /**
     * Saves the file.
     * if `HydraComponent::forceSave` is `true` and tarfet file `$filePath` already exists, it will be overwritten.
     * if `HydraComponent::forceSave` is `false` and tarfet file `$filePath` already exists, will found new file name.
     *
     * @param string $source Source file path
     * @param string $filePath Path to file in `HydraComponent::filesPath` directiry
     * @return mixed Filename or false when file cannot be saved
     * @throws \yii\base\ErrorException When extesion if forbidden
     * @see forceSave
     * @see findNewFilename()
     */
    public function saveFile($source, $filePath, $toCompress = null)
    {
        $filePath = FileHelper::normalizePath($filePath);
        $path = dirname($filePath);
        $name = $this->getFilename($filePath);
        $extension = $this->getExtension($filePath);
        if (in_array($extension, $this->forbiddenExtensions))
        {
            throw new \yii\base\InvalidValueException($filePath . ' is forbidden file format');
        }
        $name = $this->findNewFilename($path, $name, $extension);
        $this->traceDir($path);

        $result = copy($source, $this->filesPath . $path . '/' . $name . '.' . $extension);

        return $result ? $path . '/' . $name . '.' . $extension : false;
    }

    /**
     * Generates path to resized image.
     * You can set `$resolution`:
     * 1. In format '**Width**x**Height**Croptype' (example: 150x100p).
     * 1. By name (example: 'newsView').
     *
     * All resolutions must be setted in `HydraComponent::resolutions`.
     *
     * `Croptype` must be 'p' or 'i'. 'p' means that image will cropped proportionally. 'i' means that image will inscribed to resolution.
     *
     * @param string $filePath Path to source file.
     * @param string $resolution Resolution or name of resolution.
     * @return string Path to resized image in cache.
     * @throws \yii\base\ErrorException When resolution is not allowed.
     * @throws \yii\base\InvalidValueException When file `$filePath` is not image.
     * @see $resolutions
     */
    public function getCacheUrl($filePath, $resolution)
    {
        $resolution = $this->getResolution($resolution);
        if ($resolution === false)
        {
            throw new \yii\base\ErrorException('Undefined image resolution');
        }
        $path = dirname($filePath);
        $name = $this->getFilename($filePath);
        $extension = $this->getExtension($filePath);

        if (!in_array($extension, $this->imagesExtensions))
        {
            throw new \yii\base\InvalidValueException($filePath . ' is not image');
        }

        return $this->cacheUrl . $path . ($path == '/' ? '' : '/') . $name . '-' . $resolution . '.' . $extension;
    }

    public function getFilesUrl($filePath)
    {
        return $this->filesUrl . $filePath;
    }

    /**
     * Checks `$resolution` to exists and returned pattern.
     *
     * @param string $resolution Pattern or name of resolution.
     * @return mixed Pattern or false whet resolution doesn't exists in `HydraComponent::resolutions`.
     * @see $resolutions
     */
    protected function getResolution($resolution)
    {
        if (empty($resolution))
        {
            return false;
        }
        if (isset($this->resolutions[$resolution]))
        {
            return $this->resolutions[$resolution];
        }
        if (in_array($resolution, $this->resolutions))
        {
            return $resolution;
        }

//        if(preg_match('|^([0-9]+)x([0-9]+)[pi]$|Uis', $resolution)) return $resolution;
        return false;
    }

    /**
     * Make directory.
     *
     * You can make directory when even parent directory doesn't exists.
     *
     * `$in` must be 'files' or 'cache'.
     *
     * @param string $dirPath Path to directory
     * @param string $in Base path to directory.
     * @return boolean Returns true when directoy exists or made successfully
     * @throws \yii\base\InvalidParamException When invalid `$in` value
     * @throws \yii\base\InvalidValueException When `$dirPath` is invalid.
     */
    public function traceDir($dirPath, $in = 'files')
    {
        if (!in_array($in, ['files', 'cache']))
        {
            throw new \yii\base\InvalidParamException('$in must be \'files\' or \'cache\'');
        }
        $basePath = $in == 'files' ? $this->filesPath : $this->cachePath;
        if (is_dir($basePath . $dirPath))
        {
            return true;
        }
        if (strpos($dirPath, '..') !== false)
        {
            throw new \yii\base\InvalidValueException('Can not create directory: ' . $dirPath);
        }
        $names = explode('/', $dirPath);
        $names = array_slice($names, 1);
        $clod = '';
//        print_r($names);
//        die($dirPath);
        foreach ($names as $name)
        {
            if (empty($name))
            {
                continue;
            }
            $clod = $clod . '/' . $name;
            if (!is_dir($basePath . $clod))
            {
                mkdir($basePath . $clod);
            }
        }

        return true;
    }

    /**
     * Deletes the file and all cache files when they exists.
     *
     * @param string $filePath Path to file.
     * @return boolean Returns `true` on success or `false` on failure.
     * @throws \yii\base\InvalidParamException When file is not exists.
     */
    public function deleteFile($filePath)
    {
        if (strpos($filePath, '..') !== false)
        {
            throw new \yii\base\InvalidValueException('Can not delete file: ' . $filePath);
        }
        if (!$this->hasFile($filePath))
        {
            throw new \yii\base\InvalidParamException($filePath . ' is not exists');
        }
        $this->deleteCacheFiles($filePath);

        return unlink($this->filesPath . $filePath);
    }

    /**
     * Deletes the directory in `HydraComponent::filesPath`.
     * Deletes directory in `HydraComponent::cachePath` if it exists.
     *
     * @param string $dirPath Path to directory.
     * @return boolean Returns `true` on success or `false` on failure.
     * @throws \yii\base\InvalidValueException Whene `$dirPath` is invalid.
     */
    public function removeDirectory($dirPath)
    {
        if (!$this->hasDirectory($dirPath))
        {
            return false;
        }
        FileHelper::removeDirectory($this->filesPath . $dirPath);
        if (is_dir($this->cachePath . $dirPath))
        {
            FileHelper::removeDirectory($this->cachePath . $dirPath);
        }

        return true;
    }

    public function deleteCacheFiles($filePath)
    {
        $files = $this->findCacheFiles($filePath);
        if (empty($files))
        {
            return;
        }
        foreach ($files as $file)
        {
            unlink($file['fullpath']);
        }
    }

    /**
     * Returns name of file.
     *
     * @param string $filePath Path to file.
     * @return string Name of file.
     */
    public function getFilename($filePath)
    {
        return basename($filePath, '.' . $this->getExtension($filePath));
    }

    /**
     * Returns extension of file.
     *
     * @param string $filePath Path to file.
     * @return string Extension of file.
     */
    public function getExtension($filePath)
    {
        return strtolower(substr(strrchr($filePath, '.'), 1));
    }

    /**
     * Find new name for file if that file exists.
     *
     * If `HydraComponent::forceSave` is `true` retunts original $filePath.
     *
     * @param string $filePath Path to source file
     * @return string new filePath
     * @see forceSave
     */
    protected function findNewFilename($path, $name, $extension)
    {
        if ($this->forceSave)
        {
            return $name;
        }

        $files = $this->ls($path, 'files', true);
        if (empty($files))
        {
            return $name;
        }

        $number = '';
        while (true)
        {
            $newName = $name . $number;
            $exists = false;
            foreach ($files as $file)
            {
                if ($file['name'] . '.' . $file['extension'] == $newName . '.' . $extension)
                {
                    $exists = true;
                    break;
                }
            }
            if ($exists === false)
            {
                return $newName;
            }
            if (empty($number))
            {
                $number = 1;
            } else
            {
                $number++;
            }
        }
    }

    /**
     * Checks the file to exist.
     *
     * @param string $filePath Source file
     * @return boolean
     */
    public function hasFile($filePath)
    {
        return file_exists($this->filesPath . $filePath);
    }

    public function hasDirectory($dirPath)
    {
        return is_dir($this->filesPath . $dirPath);
    }

    /**
     * Returns information about file.
     *
     * @param string $filePath Path to source file
     * @return array Information about file
     */
    public function getFileInfo($filePath, $in = 'files', $public = false)
    {
        if (!in_array($in, ['files', 'cache']))
        {
            throw new \yii\base\InvalidParamException('$in must be \'files\' or \'cache\'');
        }
        $basePath = $in == 'files' ? $this->filesPath : $this->cachePath;

        $pathInfo = pathinfo($this->filesPath . $filePath);
        $stat = stat($basePath . $filePath);

        $result = [
            'filename'  => $pathInfo['basename'],
            'filepath'  => $filePath,
            'name'      => $pathInfo['filename'],
            'extension' => $pathInfo['extension'],
            'url'       => $this->filesUrl . $filePath,
            'size'      => $stat['size'],
            'atime'     => $stat['atime'],
            'mtime'     => $stat['mtime'],
            'isdir'     => false,
        ];

        if ($public === false)
        {
            $result['fullpath'] = $pathInfo['dirname'] . '/' . $pathInfo['basename'];
            if ($in == 'files')
            {
                $result['caches'] = $this->findCacheFiles($filePath);
            }
        }

        return $result;
    }

    public function getDirectoryInfo($dirPath, $in = 'files', $public = false)
    {
        $pathInfo = pathinfo($this->filesPath . $dirPath);

        echo $dirPath;
        $info = [
            'filename' => $pathInfo['basename'],
            'filepath' => $dirPath,
            'name'     => $pathInfo['basename'],
            'url'      => ($in == 'files' ? $this->filesUrl : $this->cacheUrl) . $dirPath,
            'isdir'    => true,
        ];
        if ($public === false)
        {
            $result['fullpath'] = $pathInfo['dirname'] . '/' . $pathInfo['basename'];
        }

        return $info;
    }

    /**
     * Returns list of resized pictures.
     *
     * If files is not found returns empty array
     * @param string $filePath Path to source file
     * @return array List of cached files
     */
    public function findCacheFiles($filePath)
    {
        $name = $this->getFilename($filePath);
        $extension = $this->getExtension($filePath);
        if (!in_array($extension, $this->imagesExtensions))
        {
            return [];
        }
        $path = dirname($filePath);
//        die($path);
        $files = $this->ls($path, 'cache');
        if (empty($files))
        {
            return [];
        }
        $cacheList = [];
        foreach ($files as $file)
        {
            if (preg_match('|' . $name . '\-([0-9]+)x([0-9]+)([ip])\.' . $extension . '|iu', $file['filename'],
                $matches))
            {
                $cacheList[] = $file;
            }
        }

        return $cacheList;
    }

    /**
     * Delete all files in cache
     * Use it careful!!! Removal may take a long time.
     */
    public function clearCache()
    {
        $files = $this->ls('/', 'cache');
        foreach ($files as $file)
        {
            if (is_dir($this->cachePath . '/' . $file))
            {
                $this->$this->recurciveDelete($this->cachePath . '/' . $file);
            } else
            {
                unlink($this->cachePath . '/' . $file);
            }
        }
    }

    /**
     * Service method for recurcive delete
     * @param string $dir
     */
    protected function recurciveDelete($dir)
    {
        if ($objs = glob($dir . "/*"))
        {
            foreach ($objs as $obj)
            {
                is_dir($obj) ? $this->recurciveDelete($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    /**
     * Deletes all resized images
     *
     * @param string $filePath Path to source file
     * @return void
     */
    public function clearFileCache($filePath)
    {
        $files = $this->findCacheFiles($filePath);
        if (empty($files))
        {
            return;
        }
        foreach ($files as $file)
        {
            unset($file['fullpath']);
        }
        $dirPath = dirname($filePath);
        if (empty($this->ls($dirPath)))
        {
            rmdir($dirPath);
        }
    }

    /**
     * Returns list of directories and files.
     *
     * @param string $dirPath Path to directory
     * @param string $in Flag to read in `HydraComponent::filesPath` or `HydraComponent::cachePath`
     *
     * @return array List of files.
     * @throws \yii\base\InvalidParamException Invalid `$in`
     * @throws \yii\base\InvalidValueException `$dirPath` is not directory
     */
    public function ls($dirPath, $in = 'files', $public = false)
    {
        if (!in_array($in, ['files', 'cache']))
        {
            throw new \yii\base\InvalidParamException('$in must be \'files\' or \'cache\'');
        }
        $basePath = $in == 'files' ? $this->filesPath : $this->cachePath;
        if ($dirPath == '/')
        {
            $dirPath = '';
        }
        if (!is_dir($basePath . $dirPath))
        {
            return null;
        }
        $list = scandir($basePath . $dirPath);
        $result = [];

        foreach ($list as $file)
        {
            if ($file == '.' || $file == '..')
            {
                continue;
            }

            if (is_dir($basePath . $dirPath . '/' . $file))
            {
                $result[] = $this->getDirectoryInfo($dirPath . '/' . $file, $in, $public);
            } else
            {
                $file = $this->getFileInfo($dirPath . '/' . $file, $in, $public);
                $result[] = $file;
            }
        }

        return $result;
    }

    public function generateCacheimage($fileInfo)//$sourceFile, $width, $height, $cropType)
    {
        $fileInfo = $this->parseCachePath($fileInfo);

        if (!file_exists($fileInfo['sourceFilePath']))
        {
            throw new \yii\base\InvalidParamException($fileInfo['sourceUrl'] . ' is not exists');
        }

        $imagine = Image::getImagine();
        $image = $imagine->open($fileInfo['sourceFilePath']);

        if ($fileInfo['cropType'] == 'p')
        {
            $mode = ImageInterface::THUMBNAIL_INSET;
        } else
        {
            if ($fileInfo['cropType'] == 'i')
            {
                $mode = ImageInterface::THUMBNAIL_OUTBOUND;
            } else
            {
                throw new \yii\base\UnknownPropertyException('Udefined crop type');
            }
        }
        $image = $image->thumbnail(new Box($fileInfo['width'], $fileInfo['height']), $mode);
        $this->traceDir($fileInfo['sourceDir'], 'cache');
        $image->save($fileInfo['cacheFilePath']);

        return $image;
    }

    public function parseCachePath($filePath)
    {
        $preg = preg_match('#' . $this->cacheUrl . '/(.*)-([0-9]+)x([0-9]+)([ip])\.(' . implode('|',
                $this->imagesExtensions) . ')$#uUis', $filePath, $data);
        if (!$preg)
        {
            throw new \yii\base\InvalidValueException('Can\'t parce url');
        }
        $dirname = dirname($data[1]);
        $result = [
            'cacheDirPath'   => $this->cachePath . '/' . ($dirname == '.' ? '' : $dirname),
            'cacheFilePath'  => $this->cachePath . '/' . $data[1] . '-' . $data[2] . 'x' . $data[3] . $data[4] . '.' . $data[5],
            'sourceFilePath' => $this->filesPath . '/' . $data[1] . '.' . $data[5],
            'sourceUrl'      => $this->filesUrl . '/' . $data[1] . '.' . $data[5],
            'sourceDir'       => ($dirname == '.' ? '' : '/'.$dirname),
            'width'          => $data[2],
            'height'         => $data[3],
            'cropType'       => $data[4],
        ];

        return $result;
    }

    public function getBreadcrumbs($path)
    {
        if (empty($path))
        {
            return [];
        }
        $pieces = explode('/', $path);
        $result = [];
        $pathArray = [];
        foreach ($pieces as $piece)
        {
            if ($piece == '')
            {
                continue;
            }
            $pathArray[] = $piece;
            $result[] = [
                'path'  => '/' . implode('/', $pathArray),
                'label' => $piece,
            ];
        }

        return $result;
    }

    public function isImage($extesion)
    {
        return in_array($extesion, $this->imagesExtensions);
    }

}
