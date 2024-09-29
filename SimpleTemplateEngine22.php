<?php
class SimpleTemplateEngine {
    protected $template;
    protected $data;

    public function __construct($template) {
        $this->template = $template;
    }

    public function render($data) {
        $this->data = $data;

        // ループ処理をサポート
        // if文の処理を追加
        $template = preg_replace_callback('/{{foreach (.+?) as (.+?)}}(.*?){{\/f
oreach}}/s', [$this, 'replaceLoop'], $this->template);
        $template = preg_replace_callback('/{{if (.*?)}}(.*?){{else}}(.*?){{\/if
}}/s', [$this, 'replaceIfElse'], $template);
        $template = preg_replace_callback('/{{if (.*?)}}(.*?){{\/if}}/s', [$this
, 'replaceIf'], $template);
        return preg_replace_callback('/{{(.*?)}}/', [$this, 'replace'], $templat
e);

    }

    protected function replace($matches) {
        $key = trim($matches[1]);
        return array_key_exists($key, $this->data) ? $this->data[$key] : $matches[0];
    }

    protected function replaceLoop($matches) {
        $arrayKey = trim($matches[1]);
        $itemKey = trim($matches[2]);
        $content = $matches[3];

        if (!isset($this->data[$arrayKey]) || !is_array($this->data[$arrayKey]))
 {
            return '';
        }

        $output = '';
        $result = '';
        foreach ($this->data[$arrayKey] as $item) {
            $start = strpos($this->template, "{{foreach");
            $start = strpos($this->template, "}}", $start) + 2;
            $end = strpos($this->template, "{{/foreach");
            $t = substr($this->template, $start, $end - $start);

            $this->data = array_merge($this->data, [$itemKey => $item]);
            $output = preg_replace_callback('/{{(.*?)}}/', [$this, 'replace'], $t );
            $result .= preg_replace_callback('/{{if (.*?)}}(.*?){{else}}(.*?){{\/if}}/s', [$this, 'replaceIfElse'], $output);

        }

        return $result;
    }

    protected function evaluateCondition($condition) {
        // 変数の置換
        foreach ($this->data as $key => $value) {
            $condition = str_replace($key, var_export($value, true), $condition);
        }

    preg_match("/'([^']+)' (==|!=|>|<|>=|<=) '([^']+)'/", $condition, $matches);
    if (count($matches) === 4) {
        $leftOperand = $matches[1];
        $operator = $matches[2];
        $rightOperand = $matches[3];

        // オペレーターに応じた評価
        switch ($operator) {
            case '==':
                return $leftOperand === $rightOperand;
            case '!=':
                return $leftOperand !== $rightOperand;
            case '>':
                return $leftOperand > $rightOperand;
            case '<':
                return $leftOperand < $rightOperand;
            case '>=':
                return $leftOperand >= $rightOperand;
            case '<=':
                return $leftOperand <= $rightOperand;
            default:
                return false; // 無効なオペレーター
        }
    } else {
      return $condition;
    }

    return false;



    }

    protected function replaceIf($matches) {
        $condition = trim($matches[1]);
        $content = $matches[2];

        if ($this->evaluateCondition($condition) == true) {
            return $content;
        }

        return '';
    }

    protected function replaceIfElse($matches) {
        $condition = trim($matches[1]);
        $ifContent = $matches[2];
        $elseContent = $matches[3];
        if ($this->evaluateCondition($condition)) {
            return $ifContent;
        }

        return $elseContent;
    }
}
