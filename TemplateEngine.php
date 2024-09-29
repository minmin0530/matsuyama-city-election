<?php

class TemplateEngine {
    protected $variables = [];

    // 変数を設定する
    public function assign($key, $value) {
        $this->variables[$key] = $value;
    }

    // テンプレートを表示する
    public function render($template) {
        // テンプレートの内容を取得
        $content = file_get_contents($template);

        // 変数を置換
        foreach ($this->variables as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }

        // 変数が設定されていない場合の処理
        $content = preg_replace('/{{\s*([^}]+)\s*}}/', '', $content);

        return $content;
    }
}
