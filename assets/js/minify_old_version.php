<?php
    /**
     * Concatenate an array of files into a string
     *
     * @param $files
     * @return string
     */
    function concatenateFiles($files)
    {
        $buffer = '';

        foreach($files as $file) {
            $buffer .= file_get_contents(__DIR__ . '/' . $file);
        }

        return $buffer;
    }

    /**
     * @param $files
     * @return mixed|string
     */
    function minifyJS($files) {
        $buffer = concatenateFiles($files);

        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        $buffer = str_replace(["\r\n","\r","\t","\n",'  ','    ','     '], '', $buffer);
        $buffer = preg_replace(['(( )+\))','(\)( )+)'], ')', $buffer);

        return $buffer;
    }


    // DON'T MINIFY JQUERY / BOOTSTRAP / ANIMSITION
    $js = minifyJS([
        'ajax.js',
        'site.js'
    ]);

    // WRITE INTO FILE
    $fp = fopen('minified.php', 'w');
    fwrite($fp, $js);
    fclose($fp);

    echo 'JavaScript Files have been Minified.';
