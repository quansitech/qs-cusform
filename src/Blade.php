<?php
namespace CusForm;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\PhpEngine;

class Blade{
    protected $filesystem;
    protected $cache_path;
    protected $compiler;
    protected $engine;

    public function __construct(){
        $this->filesystem = new Filesystem();

        $this->cache_path = LARA_DIR . '/storage/cache';
        $this->prepareCachePath();

        $this->compiler = new BladeCompiler($this->filesystem, $this->cache_path);
        $this->engine = new PhpEngine();
    }

    protected function prepareCachePath() : void{
        if(!$this->filesystem->exists($this->cache_path)){
            $this->filesystem->makeDirectory($this->cache_path, 0755, true);
        }
    }

    public function compilerString(string $content): string{

        $cache_file = $this->cache_path . '/' . sha1(microtime()) . '.php';
        $compiled = $this->compiler->compileString($content);
        $this->filesystem->put($cache_file, $compiled);

        $html = $this->engine->get($cache_file);
        $this->filesystem->delete($cache_file);
        return $html;
    }
}