<?php

namespace Fuel\Tasks;

/**
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @copyright  2012 Kenji Suzuki
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * @link       https://github.com/kenjis/fuelphp-tools
 *
 * @author     Hiroshi Sugawara
 * @copyright  2015 Hiroshi Sugawara
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 */

class Autocomplete
{

    static $class_definition = '';
    private static $list = array();
    private static $extended_list = array();

    public static function run()
    {
        static::create_autocomplete(COREPATH . 'classes', 'Fuel\\Core\\');
        static::create_autocomplete(PKGPATH . 'auth/classes', 'Auth\\');
        static::create_autocomplete(PKGPATH . 'email/classes', 'Email\\');
//        static::create_autocomplete(PKGPATH . 'oil/classes', 'Oil\\');
//        static::create_autocomplete(PKGPATH . 'orm/classes', 'Orm\\');
//        static::create_autocomplete(PKGPATH . 'parser/classes', 'Parser\\');

        static::$class_definition = '<?php' . "\n\n" . static::$class_definition;
        $file = realpath(APPPATH . '../../') . DIRECTORY_SEPARATOR . '_autocomplete.php';
        $ret = file_put_contents($file, static::$class_definition);

        if ($ret === false) {
            echo 'Can\'t write to ' . $file . "\n";
        } else {
            echo $file . ' was created.' . "\n";
        }
    }

    public static function create_autocomplete($path, $namespace)
    {
        $filelist = \File::read_dir($path);
        self::$list = array();
        static::convert_filelist($filelist);

        static::generate_class_definition(self::$list, $path, $namespace);
    }

    private static function generate_class_definition(Array $filelist, $path, $namespace)
    {
        foreach ($filelist as $file) {
            $lines = file($path . '/' . $file);

            foreach ($lines as $line) {
                if (preg_match('/^class (\w+)/', $line, $matches)) {
                    $class_name = $matches[1];

                    if (in_array($class_name, self::$extended_list)) {
                        static::$class_definition .= '// ';
                    }

                    static::$class_definition .= 'class ' . $class_name;
                    static::$class_definition .= ' extends ' . $namespace . $class_name;
                    static::$class_definition .= ' {}' . "\n";
                } else {
                    if (preg_match('/^abstract class (\w+)/', $line, $matches)) {
                        $class_name = $matches[1];

                        if (in_array($class_name, self::$extended_list)) {
                            static::$class_definition .= '// ';
                        }

                        static::$class_definition .= 'abstract class ' . $class_name;
                        static::$class_definition .= ' extends ' . $namespace . $class_name;
                        static::$class_definition .= ' {}' . "\n";
                    }
                }
            }
        }
    }

    /**
     * Convert Filelist Array to Single Dimension Array
     *
     * @param  array   filelist array of \File::read_dir()
     * @param  string  directory
     * @return array
     */
    private static function convert_filelist($arr, $dir = '')
    {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                static::convert_filelist($val, $dir . $key);
            } else {
                self::$list[] = $dir . $val;
            }
        }
    }

}
