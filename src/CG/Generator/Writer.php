<?php

namespace CG\Generator;

class Writer
{
    private $content = '';
    private $indentationSpaces = 4;
    private $indentationLevel = 0;

    public function indent()
    {
        $this->indentationLevel += 1;

        return $this;
    }

    public function outdent()
    {
        $this->indentationLevel -= 1;

        if ($this->indentationLevel < 0) {
            throw new \RuntimeException('The identation level cannot be less than zero.');
        }

        return $this;
    }

    public function writeln($content)
    {
        $this->write($content."\n");

        return $this;
    }

    public function write($content)
    {
        $lines = explode("\n", $content);
        for ($i=0,$c=count($lines); $i<$c; $i++) {
            if ($this->indentationLevel > 0
                && !empty($lines[$i])
                && (empty($this->content) || "\n" === substr($this->content, -1))) {
                $this->content .= str_repeat(' ', $this->indentationLevel * $this->indentationSpaces);
            }

            $this->content .= $lines[$i];

            if ($i+1 < $c) {
                $this->content .= "\n";
            }
        }

        return $this;
    }

    public function rtrim()
    {
        $addNl = "\n" === substr($this->content, -1);
        $this->content = rtrim($this->content);

        if ($addNl) {
            $this->content .= "\n";
        }

        return $this;
    }

    public function reset()
    {
        $this->content = '';
        $this->indentationLevel = 0;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
}