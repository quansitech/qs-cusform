<?php
namespace CusForm;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\PhpEngine;
use Think\View;

class FormilyBuilder{

    protected $apply_id;
    protected $json_schema;
    protected $mode;
    protected $post_url;

    public function __construct(int $apply_id, \stdClass $json_schema){
        $this->apply_id = $apply_id;
        $this->json_schema = $json_schema;
        $this->mode = 'readonly';
        $this->post_url = U('/admin/FormApply/edit', '', false, true);
    }

    public function setMode(string $mode){
        $this->mode = $mode;
    }

    public function setPostUrl($url){
        $this->post_url = $url;
    }

    protected function genOpt(){
        $opt = [
            'applyId' => $this->apply_id,
            'jsonSchema' => $this->json_schema,
            'mode' => $this->mode,
            'postUrl' => $this->post_url
        ];
        return json_encode($opt, JSON_PRETTY_PRINT);
    }

    protected function asset($path){
        return asset($path);
    }

    public function __toString(){
            $id = Str::uuid();

            $template = <<<template
<div id="{$id}">
</div>
<?php if(!defined(qs-custom-form)){ ?>
    <link href="{$this->asset('cusform/qsCusform.css')}" rel="stylesheet" />
    <script src="{$this->asset('cusform/moment-with-locales.js')}"></script>
    <script src="{$this->asset('cusform/react.development.js')}" ></script>
    <script src="{$this->asset('cusform/react-dom.development.js')}"></script>
    <script src="{$this->asset('cusform/antd-with-locales.min.js')}"></script>
    <script src="{$this->asset('cusform/qsCusform.bundle.js')}"></script>
    <?php define('qs-custom-form', 1); ?>
<?php } ?>
<script>
@verbatim
qsCusform.renderApply(document.getElementById('{$id}'), {$this->genOpt()});
@endverbatim
</script>
template;

        $blade = new Blade();
        return $blade->compilerString($template);
    }
}