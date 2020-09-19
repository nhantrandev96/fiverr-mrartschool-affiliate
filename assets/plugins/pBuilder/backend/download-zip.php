<?php

if ( ! function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return strpos($haystack, $needle) > -1;
    }
}

$project = json_decode(urldecode($_POST['project']));
$exporter = new Exporter();

$path = $exporter->project($project);

$file_name = basename($path);

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$file_name");
header("Content-Length: " . filesize($path));

readfile($path);

class Exporter {

    /**
     * Exports folder path.
     *
     * @var string
     */
    private $exportsPath;

    /**
     * Base directory path.
     *
     * @var string
     */
    private $baseDir;

    private $baseUrl;

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/..';
        $this->exportsPath = $this->baseDir . '/backend/';
        $this->baseUrl  = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    private function emptyFolder($path) {
        if (is_dir($path)) {
            $objects = scandir($path);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($path."/".$object) == "dir") $this->emptyFolder($path."/".$object); else unlink($path."/".$object);
                }
            }
            reset($objects);
            rmdir($path);
        }
    }

    /**
     * Create export zip file for given project.
     *
     * @param  object $project
     * @param  boolean $zip
     *
     * @return boolean/string
     */
    public function project($project)
    {
        //bail if no project found
        if ( ! $project) return false;

        $this->emptyFolder($this->exportsPath.'project');

        if ($path = $this->createFolders($project)) {
            $this->createFiles($path, $project);
            return $this->zip($path, 'project');
        }
    }

    /**
     * Create css and html files from pages in given project.
     *
     * @param  string $path
     * @param  Object $project
     *
     * @return void
     */
    public function createFiles($path, $project)
    {
        //create mail file
        $mail = @file_get_contents($this->baseDir.'/contact_me.php');
        file_put_contents($path.'mail/contact_me.php', $mail);

        $cssPaths = array();

        //get a list of custom elements
        $elems = scandir($this->baseDir.'/elements');

        //create html, css and js files for each page in the project
        foreach ($project->pages as $key => $page) {

            //create a file with user custom css
            if (isset($page->css) && $page->css) {
                @unlink($path."css/{$page->name}-stylesheet.css");

                $css = $this->handleImages($page->css, $path, 'css');

                if (@file_put_contents($path."css/{$page->name}-stylesheet.css", $css)) {
                    $cssPaths[$page->name] = "css/{$page->name}-stylesheet.css";
                }
            }

            if (isset($page->libraries)) {
                $scripts = $this->handleLibraries($page->libraries, $path);
            }

            //create a file with user custom js
            if (isset($page->js) && $page->js) {
                if (@file_put_contents($path."js/{$page->name}-scripts.js", $page->js)) {
                    $jsPaths[$page->name] = "js/{$page->name}-scripts.js";
                }
            }

            //create html files
            if ($page->html) {

                $cssPaths = $this->handleCustomElementsCss($elems, $cssPaths, $page, $path);

                //bootstrap
                $page->html = preg_replace('/<link id="main-sheet".+?>/', '', $page->html);
                $page->html = preg_replace('/<link.+?font-awesome.+?>/', '', $page->html);

                $bs = @file_get_contents($this->baseDir.'/public/css/bootstrap.min.css');
                @file_put_contents($path.'css/bootstrap.min.css', $bs);

                $page->html = preg_replace('/(<\/head>)/ms', "\n\t<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">\n$1", $page->html);

                //font-awesome
                $page->html = preg_replace('/(<\/head>)/ms', "\n\t<link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css\">\n$1", $page->html);

                if (isset($cssPaths[$page->name])) {
                    $page->html = preg_replace('/(<\/head>)/ms', "\n\t<link rel=\"stylesheet\" href=\"".$cssPaths[$page->name]."\">\n$1", $page->html);
                }

                if (isset($jsPaths[$page->name])) {
                    $page->html = preg_replace('/(<\/body>)/ms', "<script id=\"main-script\" src=\"".$jsPaths[$page->name]."\"></script>\n$1", $page->html);
                }

                if (isset($scripts) && $scripts) {

                    //if we have any custom js for this page insert libraries before it
                    if (isset($jsPaths[$page->name])) {
                        $page->html = preg_replace('/(<script id="main-script".+?<\/script>)/ms', "$scripts$1", $page->html);

                        //otherwise insert libraries before closing body tag
                    } else {
                        $page->html = preg_replace('/(<\/body>)/ms', "$scripts\n$1", $page->html);
                    }
                }

                $page->html = $this->handleImages($page->html, $path, 'html');
                $page->html = $this->handleMeta($page->html, $page);
                $page->html = $this->handlePreviews($page->html);
                $page->html = preg_replace('/(<base.+?>)/ms', '', $page->html);

                file_put_contents($path."{$page->name}.html", $page->html);
            }
        }
    }

    private function handlePreviews($html)
    {
        return preg_replace('/<img.+?data-src="(.+?)".+?>/ims', '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="$1"></iframe></div>', $html);
    }

    /**
     * Append any used custom elements css to css file.
     *
     * @param  array      $elems
     * @param  array      $paths
     * @param  object     $page
     * @param  string     $path
     *
     * @return array
     */
    private function handleCustomElementsCss(array $elems, array $paths, $page, $path)
    {
        $elemsCss = '';

        //search for any custom elements in the page html
        foreach ($elems as $element) {
            if ($element !== '.' && $element !== '..') {

                //convert element to dash-case, remove .html and remove last s to convert to singular
                $name = rtrim(str_replace('.html', '', strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', "-$1", $element))), 's');

                if (str_contains($page->html, $name)) {

                    //get css from the element
                    $elemContents = file_get_contents($this->baseDir."/elements/$element");
                    preg_match('/<style.*?>(.+?)<\/style>/s', $elemContents, $css);

                    //if custom element has any css append it to the already existing page css.
                    if (isset($css[1]) && $css = trim($css[1])) {
                        $elemsCss .= "\n\n$css";
                    }
                }
            }
        }

        if ($elemsCss) {
            @file_put_contents($path."css/{$page->name}-stylesheet.css", trim($elemsCss), FILE_APPEND);
            $paths[$page->name] = "css/{$page->name}-stylesheet.css";
        }

        return $paths;
    }

    /**
     * Add meta tags to head html.
     *
     * @param  string $html
     * @param  $page
     *
     * @return string
     */
    private function handleMeta($html, $page) {
        $meta = '';

        if (isset($page->title) && $page->title) {
            $meta .= "\n\t<title>{$page->title}</title>\n";
            $meta .= "\t<meta name=\"title\" content=\"{$page->title}\">\n";
        }

        if (isset($page->tags) && $page->tags) {
            $meta .= "\t<meta name=\"tags\" content=\"{$page->tags}\">\n";
        }

        if (isset($page->description) && $page->description) {
            $meta .= "\t<meta name=\"description\" content=\"{$page->description}\">\n";
        }

        return preg_replace('/(<meta name="viewport" content="width=device-width, initial-scale=1">)/', "$1$meta", $page->html);
    }

    /**
     * Convert local relative path to absolute one.
     *
     * @param  string $path
     * @return string
     */
    private function relativeToAbsolute($path)
    {
        if (str_contains($path, '//')) {
            return $path;
        }

        $path = str_replace('"', "", $path);
        $path = str_replace("'", "", $path);
        $path = str_replace("../", "", $path);

        return $this->baseDir.'/'.$path;
    }

    /**
     * Copy any local images used in html/css to export folder.
     *
     * @param  string $string
     * @param  string $path
     * @param  string $type
     *
     * @return string
     */
    private function handleImages($string, $path, $type)
    {
        preg_match_all('/url\((.+?)\)/ms', $string, $matches1);
        preg_match_all('/<img.*?src="(.+?)".*?>/ms', $string, $matches2);

        $matches = array_merge($matches1[1], $matches2[1]);

        //include any local images used in css or html in the zip
        if ( ! empty($matches)) {
            foreach ($matches as $url) {

                if (str_contains($url, $this->baseUrl) || ! str_contains($url, '//')) {
                    $absolute = $this->relativeToAbsolute($url);

                    try {
                        @copy($absolute, $path.'images/'.basename($absolute));
                    } catch (\Exception $e) {
                        continue;
                    }

                    if ($type == 'css') {
                        $string = str_replace($url, '../images/'.basename($absolute), $string);
                    } else {
                        $string = str_replace($url, 'images/'.basename($absolute), $string);
                    }
                }
            }
        }

        return $string;
    }

    /**
     * Cope any local js libraries to export folder.
     *
     * @param  string  $libraries
     * @param  string  $path
     *
     * @return string  scripts html to insert before closing body tag
     */
    private function handleLibraries($libraries, $path)
    {

        $scripts = "<script src=\"js/jquery.js\"></script>\n<script src=\"js/bootstrap.js\"></script>\n";

        @$this->fs->copy($this->baseDir.'/public/js/vendor/jquery.js', $path.'js/jquery.js', true);
        @$this->fs->copy($this->baseDir.'/public/js/vendor/bootstrap/bootstrap.min.js', $path.'js/bootstrap.js', true);

        $libraries = json_decode($libraries);

        if ($libraries) {
            foreach ($libraries as $library) {

                if (is_string($library)) {
                    $library = Library::where('name', $library)->first();
                }

                if ( ! str_contains($library->path, '//')) {
                    $absolute = $this->relativeToAbsolute($library->path);

                    try {
                        @$this->fs->copy($absolute, $path.'js/'.basename($absolute), true);
                    } catch (\Exception $e) {
                        continue;
                    }

                    $scripts .= '<script src="js/'.basename($library->path)."\"></script>\n";
                } else {
                    $scripts .= '<script src="'.$library->path."\"></script>\n";
                }
            }
        }

        return $scripts;
    }

    /**
     * Create a folder structure to hold export files.
     *
     * @param $project
     * @return string
     */
    private function createFolders($project)
    {
        $name = 'project';

        if (is_dir($this->exportsPath.$name)) {
            return $this->exportsPath.$name.'/';
        }

        if (@mkdir($this->exportsPath.$name)) {
            @mkdir($this->exportsPath.$name.'/css');
            @mkdir($this->exportsPath.$name.'/js');
            @mkdir($this->exportsPath.$name.'/images');
            @mkdir($this->exportsPath.$name.'/mail');

            return $this->exportsPath.$name.'/';
        }
    }

    /**
     * Zip files and folders at the given path.
     *
     * @param  string $path
     * @param  string $name archive name
     *
     * @return boolean/string
     */
    private function zip($path, $id)
    {
        $realPath = realpath($path);
        $absolute = $realPath.DIRECTORY_SEPARATOR;
        $ignore   = array(realpath($this->exportsPath), $realPath);

        //delete old zip if it exists
        if (is_file($absolute.$id.'.zip')) {
            unlink($absolute.$id.'.zip');
        }

        $zip = new ZipArchive();
        $zip->open($absolute.$id.'.zip', ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($realPath), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $path = $file->getRealPath();

            if ( ! in_array($file->getRealPath(), $ignore)) {
                if (is_dir($file))
                {
                    $zip->addEmptyDir(str_replace($absolute, '', $path));
                }
                else if (is_file($file))
                {
                    $zip->addFromString(str_replace($absolute, '', $path), file_get_contents($file));
                }
            }
        }

        if ($zip->close()) {
            return $absolute.$id.'.zip';
        }
    }

    /**
     * Compile absolute url to local image from url encoded string.
     *
     * @param  string $url
     * @return string
     */
    public function decodeImageUrl($url)
    {
        return $this->baseDir.str_replace('-', '/', urldecode($url));
    }
}