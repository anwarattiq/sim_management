<?php
    require_once ('../config/globals.php');

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
            // $buffer .= file_get_contents($file);
        }

        return $buffer;
    }

    /**
     * @param $files
     * @return mixed|string
     */
    function minifyCSS($files, $local_live, $base_url)
    {
        $buffer = concatenateFiles($files);

        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(["\r\n","\r","\n","\t",'  ','    ','     '], '', $buffer);
        $buffer = preg_replace(['(( )+{)','({( )+)'], '{', $buffer);
        $buffer = preg_replace(['(( )+})','(}( )+)','(;( )*})'], '}', $buffer);
        $buffer = preg_replace(['(;( )+)','(( )+;)'], ';', $buffer);

        $buffer = str_ireplace('../../assets/fonts/', $base_url . '/assets/fonts/', $buffer);
        $buffer = str_ireplace('../assets/fonts/', $base_url . '/assets/fonts/', $buffer);
        $buffer = str_ireplace('../assets/css/', $base_url . '/assets/css/', $buffer);
        $buffer = str_ireplace('../assets/img/', $base_url . '/assets/img/', $buffer);

        return $buffer;
    }

    $files = [              
              'bootstrap.min.css',
              'animate.css',
              'meanmenu.css',
              'boxicons.min.css',
              'flaticon.css',
              'odometer.min.css',
              'owl.carousel.min.css',
              'owl.theme.default.min.css',
              'magnific-popup.min.css',
              'nice-select.css',
              'google-fonts1.css',
              'google-fonts2.css',
              'style.css',
              'responsive.css'    
            ];


    // WRITE FOR LIVE
    if (isRemote()){
      $fp = fopen(__DIR__ . '/' .'minified_live.php', 'w');
      fwrite($fp, minifyCSS($files, 'live', $base_url));
      fclose($fp);

    // WRITE FOR LOCAL
    } else {
      $fp = fopen(__DIR__ . '/' .'minified_local.php', 'w');
      fwrite($fp, minifyCSS($files, 'local', $base_url));
      fclose($fp);
    }

    echo 'CSS files have been minified.';
    // $success_message .= '<br />CSS files have been minified.';
