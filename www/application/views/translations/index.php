<?php defined('SYSPATH') or die('No direct script access.'); ?>


<h3>
    <?php echo UTF8::ucfirst(__('source_code_translations')) ?>
</h3>
<hr/>
<?php
$button =  '<button class="btn btn-success" type="button">';
$button .= '<i class="icon-refresh"></i>&nbsp;'.__('load_translations_from_sources');
$button .= '</button>';
    echo HTML::anchor(
        "translations/parse_sources",
        $button,
        array('id' => 'update_from_sources')
    );

echo "<br/>";
echo "<br/>";

?>

<table class="translation">
    <thead>
        <tr>
            <th>
              <?php echo UTF8::ucfirst(__('identifier')) ?>
            </th>
            <?php
            foreach ($languages as $language) {
                echo '<th>';
                echo $language->full_name;
                echo '</th>';
            } ?>
        </tr>
      </thead>
      <tbody>
          <?php
          ksort($translations);
          foreach ($translations as $identifier => $translation_data)
          {
              echo '<tr>';
                echo '<td class="identifier" title="'.$identifier.'">';

                  echo $identifier;
                  echo '<a class="inline_remove" href="'.URL::site('translations/destroy/'.$identifier).'"><span class="inline-remove label label-important"><i class="icon-remove"></i></span></a>';
              echo '</td>';
              foreach($languages as $language)
              {
                  echo '<td>';
                    echo '<div class="editable-area" data-identifier="'.$identifier.'" data-language-id="'.$language->id.'">';
                      echo Arr::get($translation_data, $language->name);

                      echo '<a class="inline_edit" href="#"><span class="label label-warning"><i class="icon-pencil"></i> '.UTF8::ucfirst(__('edit')).'</span></a>';
                  echo '</div>';
                  echo '</td>';
              }
              echo '</tr>';
          }
          ?>
    </tbody>
</table>
