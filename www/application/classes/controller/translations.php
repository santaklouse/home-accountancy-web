<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Translations extends Controller_Core {

    public $languages = NULL;

    protected $check_access = TRUE;

    private $php_translations_path = 'application/i18n/';
    private $js_translations_path = 'media/js/translations/';

    public function before()
    {
        parent::before();
        $this->languages = Model_Language::find_all()->records;
    }

    public function action_add_missing()
    {
        $message = Arr::get($_POST, 'message', '');
        if ( ! empty($message))
        {
            $this->add_not_exists(array(strtolower($message)));
        }
        $this->render_nothing();
    }

    public function action_regenerate_translations()
    {
        $this->translations = self::translations();
        $this->generate_js_file();
        $this->generate_php_file();
        Flash::set(
            'notice',
            mb_ucfirst(__('operation_completed'))
        );
        $this->auto_render = FALSE;
        $this->request->redirect(Url::path(array('translations/')), '302');
    }

    private function get_translations()
    {
        $translations = array();
        foreach ($this->languages as $language)
        {
            $content = include Kohana::find_file('/i18n', $language->name, 'php');
            $content = is_array($content) ? $content : array();
            foreach ($content as $identifier => $translation)
            {
                $translations[$identifier][$language->name] = $translation;
            }
        }
        return array_merge($this->get_not_translated($translations), $translations);
    }

    private function get_not_translated($translations = array())
    {
        $not_translated = array();
        foreach ($this->languages as $language)
        {
            $content = include Kohana::find_file('/i18n', 'not_translated', 'php');
            $content = is_array($content) ? $content : array();
            foreach ($content as $identifier => $translation)
            {
                if ( ! Arr::get($translation, $identifier))
                    $not_translated[$identifier][$language->name] = $translation;
            }
        }
        return $not_translated;
    }

    public function action_index()
    {
        $this->register_js_file(array(
            'name' => 'libs',
            'files' => array(
                'lib/loading_icon',
                'lib/live_dialog',
                'lib/pseudo_dialog',

            ),
        ));
        $this->view->translations = $this->get_translations();
        $this->view->languages = $this->languages;
    }

    public function action_update()
    {
        $languages = array();
        foreach ($this->languages as $language)
        {
            $languages[$language->id] = $language;
        }
        $language = $languages[Arr::get($_REQUEST, 'language_id')];

        $this->save_translation(
            Arr::get($_REQUEST, 'identifier'),
            Arr::get($_REQUEST, 'translation'),
            $language
        );
        $this->render_nothing();
    }

    public function action_destroy()
    {
        $identifier = trim($this->request->param('id'));
        $not_translated = include Kohana::find_file('/i18n', 'not_translated', 'php');
        $in_not_translated = isset($not_translated[$identifier]);

        if ($in_not_translated)
        {
            unset($not_translated[$identifier]);
        }
        $this->save_php_file($not_translated, 'not_translated');
        foreach ($this->languages as $language)
        {
            $translated = include Kohana::find_file('/i18n', $language->name, 'php');
            $in_translated = Arr::get($translated, $identifier);
            if ($in_translated)
            {
                unset($translated[$identifier]);
            }
            $this->save_php_file($translated, $language->name);
        }
        $this->generate_js_file();
        $this->render_nothing();
    }

    public static function item_warnings($warnings_array, $item, $class_name)
    {
        $result = array();
        $warning = Arr::path($warnings_array, array($class_name, $item->id));
        if( ! $warning)
            return array();

        foreach ($warning as $warning_name => $warning_value)
        {
            $result[$warning_name] = $warning_value;
        }
        return $result;
    }

    private function generate_js_file()
    {
        $translations = array();
        foreach ($this->languages as $language)
        {
            $content = include Kohana::find_file('/i18n', $language->name, 'php');
            $content = is_array($content) ? $content : array();
            $translations[$language->name] = $content;
        }

        $content = array(
            "var I18n = I18n || {};\n",
            "I18n.translations = ".json_encode($translations).";\n",
        );
        file_put_contents(DOCROOT.$this->js_translations_path.'translations.js', $content);
    }

    private function generate_php_file()
    {
        $the_header = "<?php defined('SYSPATH') or die('No direct script access.'); \nreturn ";
        foreach ($this->translations as $language => $translations)
        {
            if ( ! $translations)
                continue;
            $content = $the_header.var_export($translations, true).';';
            file_put_contents(
                DOCROOT.'../modules/madeit_api/i18n/'.$language.'.php',
                $content
            );
        }
    }

    private function save_php_file($content, $file_name)
    {
        $the_header = "<?php defined('SYSPATH') or die('No direct script access.'); \nreturn ";

        $content = $the_header.var_export($content, true).';';
        return file_put_contents(
            DOCROOT.$this->php_translations_path.$file_name.'.php',
            $content
        );
    }

    private static function paths()
    {
        return array(
            '.',
            '../modules/Kohana-my-base',
        );
    }

    private function save_translation($identifier, $translation, $language)
    {
        $translated = include Kohana::find_file('/i18n', $language->name, 'php');
        $not_translated = include Kohana::find_file('/i18n', 'not_translated', 'php');

        $in_not_translated = isset($not_translated[$identifier]);

        if ($in_not_translated)
        {
            unset($not_translated[$identifier]);
        }
        $translated[$identifier] = $translation;
        $this->save_php_file($not_translated, 'not_translated');
        $this->save_php_file($translated, $language->name);
        $this->generate_js_file();
    }

    private function add_not_exists($structure)
    {
        $translated = array();
        foreach ($this->languages as $language)
        {
            $content = include Kohana::find_file('/i18n', $language->name, 'php');
            $translated = array_merge($translated, $content);
        }

        $not_translated = include Kohana::find_file('/i18n', 'not_translated', 'php');
        $content = array();
        foreach ($structure as $identifier)
        {
            $is_translated = Arr::get($translated, $identifier);
            $in_not_translated = isset($not_translated[$identifier]);
            if ( ! $is_translated && ! $in_not_translated)
            {
                $not_translated[$identifier] = '';
            }
        }
        $this->save_php_file($not_translated, 'not_translated');
    }

    public function action_parse_sources()
    {
        chdir(DOCROOT);
        $results = "";
        $dirs = self::paths();
        foreach($dirs as $path)
        {
            exec('find '.$path.' -iname "*.php" | xargs xgettext -L PHP -o - --no-wrap --keyword="__"', $result);
            $results .= implode("\n", $result);
        }
        preg_match_all(
            '/^msgid "(.+?)"\nmsgstr/m',
            $results,
            $the_structure
        );

        $the_structure = $the_structure[1];

        //removing dead translations
        $this->remove_dead_translations();

        $this->add_not_exists($the_structure);

//        Flash::set('notice', mb_ucfirst(__('parsing_finished')));
        $this->auto_render = FALSE;
        $this->request->redirect('translations/', '302');
    }

    private function remove_dead_translations()
    {
        return;
//        $paths = self::paths();
//        $content = '';
//        foreach ($paths as $path)
//        {
//            foreach (array('js', 'php') as $extension)
//            {
//                exec('find '.$path.' -iname "*.'.$extension.'"', $result);
//                foreach ($result as $filename)
//                {
//                    $content .= "\n".file_get_contents($filename);
//                }
//            }
//        }
//
//        foreach (Model_Plain_Translation::find_all()->records as $record)
//        {
//            $found = false;
//            foreach(array('"', "'") as $quote)
//            {
//                $what = $quote.$record->identifier.$quote;
//                if (strpos($content, $what) !== false)
//                {
//                    $found = true;
//                    break;
//                }
//            }
//            if ($found)
//                continue;
//            echo "going to remove ", $record->id, '(', $record->identifier, ')', PHP_EOL;
//            $record->delete();
//        }
    }

}