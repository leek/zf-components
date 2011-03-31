<?php
/**
 * @package Leek
 * @category Leek_Error
 * @see https://github.com/kurtschwarz/FFFUU-Exception
 */

/**
 * @package Leek
 * @category Leek_Error
 */
class Leek_Error
{
    const SHOW_LINES = 3;

    /**
     * @var array
     */
    protected $_ignorePaths = array(
        '\Zend\\',
    );

    /**
     * @var Exception
     */
    protected $_exception  = null;
    protected $_stack      = null;
    protected $_lastStack  = null;
    protected $_formatted  = null;
    protected $_level      = 0;
    protected $_shortTypes = array(
        'boolean'      => 'bool',
        'integer'      => 'int',
        'string'       => 'string',
        'double'       => 'double',
        'array'        => 'array',
        'object'       => 'object',
        'resource'     => 'resource',
        'NULL'         => 'null',
        'unknown type' => 'unknown',
    );

    /**
     * @param Exception $exception
     */
    public function __construct(Exception $e) {
        $this->_exception = $e;
    }

    /**
     * @return string
     */
    public function getFormattedLine() {
        $line  = '<li><div class="trace first';
        $line .= sprintf(
            '"><span class="title">In <span class="b">%s</span> around line <span class="b">%d</span>.</span><ul class="codeBlock">%s</ul></div><ul class="sub">',
                $this->_exception->getFile(),
                $this->_exception->getLine(),
                $this->_getFileSource($this->_exception->getFile(), $this->_exception->getLine(), 1)
        );
        return $line;
    }

    /**
     * @return string
     */
    public function getFormattedTrace() {
        $this->_stack     = $this->_exception->getTrace();
        $this->_formatted = null;
        $this->_level     = 0;

//        d($this->_stack);

        $this->_buildTrace();
        return $this->_formatted;
    }

    /**
     * @return string
     */
    public function getTextTrace() {
        $text  = get_class($this->_exception) . ': ' . $this->_exception->getMessage() . "<br>\r\n";
        $stack = $this->_exception->getTrace();
        $a     = array_keys($stack);
        $b     = sizeOf($a);
        for ($c = 0; $c < $b; ++$c) {
            $file  = isset($trace['file']) ? $trace['file'] : false;
            $line  = isset($trace['line']) ? $trace['line'] : false;
            $func  = '';
            $trace = &$stack[$a[$c]];
            if (!empty($trace['class'])) {
                $func .= $trace['class'] . $trace['type'];
            }
            $func .= $trace['function'];
            $text .= "&nbsp;&nbsp;at $func in $file on $line<br>\r\n";
        }

        $text .= "<br>\r\n" . str_replace("\n", "<br>\r\n", $this->_exception->getTraceAsString());
        return $text;
    }

    /**
     * @return void
     */
    private function _buildTrace() {
        if (!empty($this->_stack)) {
            $file = isset($this->_stack[0]['file']) ? $this->_stack[0]['file'] : $this->_lastStack['file'];
            $line = isset($this->_stack[0]['line']) ? $this->_stack[0]['line'] : $this->_lastStack['line'];
            $this->_formatted .= '<li><div class="trace';
            if ($this->_level == 0) {
                $this->_formatted .= ' first';
            }
            $this->_formatted .= sprintf(
                '"><span class="title">#%d In <span class="b">%s</span> around line <span class="b">%d</span>.<br><span class="code">%s</span>.</span><ul class="codeBlock">%s</ul></div><ul class="sub">',
                    $this->_level,
                    $file,
                    $line,
                    $this->_makeFunctionString(),
                    $this->_getFileSource($file, $line)
            );
            ++$this->_level;
            if ($file && $line) {
                $this->_lastStack = $this->_stack[0];
            }
            array_shift($this->_stack);
            $this->_buildTrace();
        } else {
            for ($this->_level; $this->_level > 0; --$this->_level) {
                $this->_formatted .= '</ul></li>';
            }
        }
    }

    /**
     * @param string $file
     * @param int $offset
     * @param int $showLines
     * @return string
     */
    private function _getFileSource($file, $offset, $showLines = self::SHOW_LINES) {
        $html    = null;
        $lines[] = null;

        if ($file == null || $offset == null) {
            return $html;
        }

        // Special handling of misc. paths
        foreach ($this->_ignorePaths as $path) {
            if (strpos($file, $path)) {
                return $html;
            }
        }

        $fp = fopen($file, 'r');
        do {
           $buffer  = fgets($fp, 4096);
           $lines[] = $buffer;
        } while(!feof($fp));
        fclose($fp);

        $start = $offset - $showLines;
        if ($start < 0) {
            $start = 0;
        }

        $lines = array_slice($lines, $start, ($showLines * 2) + 1, true);
        $a     = array_keys($lines);
        $b     = sizeOf($a);
        for ($c = 0; $c < $b; ++$c) {
            $line = &$lines[$a[$c]];
            //$line = preg_replace('/^\s/s', '&nbsp;', preg_replace('/\s\s/s', '&nbsp;&nbsp;', htmlspecialchars($line)));
            $html .= '<li class="line';
            if ($a[$c] == $offset) {
                $html .= ' hl';
            }
            $html .= sprintf(
                '"><div class="num">%s.</div><div class="code"><div class="border">%s</div></div></li>',
                    $a[$c],
                    $this->_highlight(str_replace('<br />', '', str_replace('?&gt;', '', str_replace('&lt;?php', '', highlight_string('<?php '.$line.' ?>', true)))))
            );
        }

        return $html;
    }

    /**
     * @return string
     * @final
     */
    private final function _makeFunctionString() {
        $html = null;
        if (!empty($this->_stack[0]['class'])) {
            $html .= $this->_stack[0]['class'] . $this->_stack[0]['type'];
        }
        $html .= $this->_stack[0]['function'] . '( ';

        $a = array_keys($this->_stack[0]['args']);
        $b = sizeOf($a);
        for ($c = 0; $c < $b; ++$c) {
            $arg   = &$this->_stack[0]['args'][$a[$c]];
            $type  = $this->_shortTypes[gettype($arg)];
            $html .= $type .' ';

            if ($type == 'string') {
                $html .= '\'<span class="i">' . $arg . '</span>\'';
            } elseif ($type == 'object') {
                $html .= '<span class="i">' . get_class($arg) . '</span>';
            } else {
                $html .= '<span class="i">' . $arg . '</span>';
            }

            if ($c < ($b-1)) {
                $html .= ', ';
            }
        }

        return "$html )";
    }

    /**
     * @param string $string
     * @return string
     * @final
     */
    private final function _highlight($string) {
        $defaults = new \stdClass;
        $defaults->string  = ini_get('highlight.string');
        $defaults->comment = ini_get('highlight.comment');
        $defaults->keyword = ini_get('highlight.keyword');
        $defaults->default = ini_get('highlight.default');
        $defaults->html    = ini_get('highlight.html');

        if (empty($defaults->string)) {
            $defaults->string = '#DD0000';
        }

        if (empty($defaults->comment)) {
            $defaults->comment = '#FF8800';
        }

        if (empty($defaults->keyword)) {
            $defaults->keyword = '#007700';
        }

        if (empty($defaults->default)) {
            $defaults->default = '#0000BB';
        }

        if (empty($defaults->html)) {
            $defaults->html = '#000000';
        }

        $string = str_replace(array(
            'style="color: ' . $defaults->string . '"',
            'style="color: ' . $defaults->comment . '"',
            'style="color: ' . $defaults->keyword . '"',
            'style="color: ' . $defaults->default . '"',
            'style="color: ' . $defaults->html . '"'
        ), array(
            'class="php-string"',
            'class="php-comment"',
            'class="php-keyword"',
            'class="php-default"',
            'class="php-html"'
        ), $string);

        return $string;
    }
}