<?php
define('lang_dependencies', array(
    "javascript" => "clike",
    "actionscript" => "javascript",
    "arduino" => "cpp",
    "aspnet" => [
        "markup",
        "csharp"
    ],
    "bison" => "c",
    "c" => "clike",
    "csharp" => "clike",
    "cpp" => "c",
    "coffeescript" => "javascript",
    "crystal" => "ruby",
    "css-extras" => "css",
    "d" => "clike",
    "dart" => "clike",
    "django" => "markup-templating",
    "ejs" => [
        "javascript",
        "markup-templating"
    ],
    "etlua" => [
        "lua",
        "markup-templating"
    ],
    "erb" => [
        "ruby",
        "markup-templating"
    ],
    "fsharp" => "clike",
    "firestore-security-rules" => "clike",
    "flow" => "javascript",
    "ftl" => "markup-templating",
    "glsl" => "clike",
    "gml" => "clike",
    "go" => "clike",
    "groovy" => "clike",
    "haml" => "ruby",
    "handlebars" => "markup-templating",
    "haxe" => "clike",
    "java" => "clike",
    "javadoc" => [
        "markup",
        "java",
        "javadoclike"
    ],
    "jolie" => "clike",
    "jsdoc" => [
        "javascript",
        "javadoclike"
    ],
    "js-extras" => "javascript",
    "js-templates" => "javascript",
    "jsonp" => "json",
    "json5" => "json",
    "kotlin" => "clike",
    "latte" => [
        "clike",
        "markup-templating",
        "php"
    ],
    "less" => "css",
    "lilypond" => "scheme",
    "markdown" => "markup",
    "markup-templating" => "markup",
    "n4js" => "javascript",
    "nginx" => "clike",
    "objectivec" => "c",
    "opencl" => "c",
    "parser" => "markup",
    "php" => [
        "clike",
        "markup-templating"
    ],
    "phpdoc" => [
        "php",
        "javadoclike"
    ],
    "php-extras" => "php",
    "plsql" => "sql",
    "processing" => "clike",
    "protobuf" => "clike",
    "pug" => [
        "markup",
        "javascript"
    ],
    "qml" => "javascript",
    "qore" => "clike",
    "jsx" => [
        "markup",
        "javascript"
    ],
    "tsx" => [
        "jsx",
        "typescript"
    ],
    "reason" => "clike",
    "ruby" => "clike",
    "sass" => "css",
    "scss" => "css",
    "scala" => "java",
    "shell-session" => "bash",
    "smarty" => "markup-templating",
    "solidity" => "clike",
    "soy" => "markup-templating",
    "sparql" => "turtle",
    "sqf" => "clike",
    "swift" => "clike",
    "tap" => "yaml",
    "textile" => "markup",
    "tt2" => [
        "clike",
        "markup-templating"
    ],
    "twig" => "markup",
    "typescript" => "javascript",
    "t4-cs" => [
        "t4-templating",
        "csharp"
    ],
    "t4-vb" => [
        "t4-templating",
        "visual-basic"
    ],
    "vala" => "clike",
    "vbnet" => "basic",
    "velocity" => "markup",
    "wiki" => "markup",
    "xeora" => "markup",
    "xquery" => "markup"
));
define('lang_alias', array(
    "html" => "markup",
    "xml" => "markup",
    "svg" => "markup",
    "mathml" => "markup",
    "js" => "javascript",
    "g4" => "antlr4",
    "adoc" => "asciidoc",
    "shell" => "bash",
    "shortcode" => "bbcode",
    "rbnf" => "bnf",
    "conc" => "concurnas",
    "cs" => "csharp",
    "dotnet" => "csharp",
    "coffee" => "coffeescript",
    "jinja2" => "django",
    "dns-zone" => "dns-zone-file",
    "dockerfile" => "docker",
    "xlsx" => "excel-formula",
    "xls" => "excel-formula",
    "gamemakerlanguage" => "gml",
    "hs" => "haskell",
    "tex" => "latex",
    "context" => "latex",
    "ly" => "lilypond",
    "emacs" => "lisp",
    "elisp" => "lisp",
    "emacs-lisp" => "lisp",
    "md" => "markdown",
    "moon" => "moonscript",
    "n4jsd" => "n4js",
    "objectpascal" => "pascal",
    "px" => "pcaxis",
    "pq" => "powerquery",
    "mscript" => "powerquery",
    "py" => "python",
    "robot" => "robotframework",
    "rb" => "ruby",
    "sln" => "solution-file",
    "rq" => "sparql",
    "trig" => "turtle",
    "ts" => "typescript",
    "t4" => "t4-cs",
    "vb" => "visual-basic",
    "xeoracube" => "xeora",
    "yml" => "yaml"
));

function find_dependencies(string $lang_name)
{
    $depend = lang_dependencies[$lang_name];
    if (isset($depend)) {
        if (is_array($depend)) {
            return $depend;
        } else {
            return [$depend];
        }
    } else {
        return array();
    }
}
function get_script_handle(string $lang_name)
{
    return "prism-lang-$lang_name";
}
function enqueue_lang_script(string $lang_name)
{
    $depend = find_dependencies($lang_name);
    wp_enqueue_script(
        get_script_handle($lang_name),
        "https://cdn.jsdelivr.net/npm/prismjs@1.20.0/components/prism-$lang_name.min.js",
        count($depend) > 0 ? array_map(function ($ele) {
            enqueue_lang_script($ele);
            return get_script_handle($ele);
        }, $depend) : array()
    );
}
function check_alias(string $lang_name)
{
    $alias = lang_alias[$lang_name];
    return isset($alias) ? $alias : $lang_name;
}
?>