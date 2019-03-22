<?php
namespace Recras;

class WordPressUnitTestCase extends \WP_UnitTestCase
{
    public function setUp()
    {
        global $recrasPlugin;

        $transient = $this->createMock(Transient::class);
        $transient->method('delete')->willReturn(0); // 0 indicates no error
        $transient->method('set')->willReturn(true);
        $transient->method('get')->will($this->returnCallback([&$this, 'transientGetCallback']));

        $recrasPlugin->transients = $transient;
    }

    public function createPostAndGetContent(string $content): string
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => $content,
        ]);
        return apply_filters('the_content', $post->post_content);
    }

    private function package()
    {
        return (object) [
            'id' => 7,
            'weergavenaam' => 'Actieve Familiedag',
            'arrangement' => 'Familiedag',
            'uitgebreide_omschrijving' => 'Uitgebreide omschrijving van dit arrangement',
            'programma' => [
                (object) [
                    'begin' => 'PT0H0M0S',
                    'eind' => 'PT4H15M0S',
                    'omschrijving' => 'Eerste activiteit',
                ]
            ],
            'image_filename' => '/api2/arrangementen/7/afbeelding',
            'aantal_personen' => 10,
            'mag_online' => true,
            'prijs_totaal_exc' => 385.6619366911,
            'prijs_totaal_inc' => 415,
            'prijs_pp_exc' => 38.56619366911,
            'prijs_pp_inc' => 41.5,
        ];
    }

    public function transientGetCallback(string $name)
    {
        if (preg_match('~^([a-z]+)_arrangements$~', $name)) {
            return [
                7 => $this->package(),
            ];
        }
        if (preg_match('~^([a-z]+)_arrangement_([\d]+)$~', $name)) {
            return $this->package();
        }
        if (preg_match('~^([a-z]+)_contactform_1_arrangements$~', $name)) {
            return [
                (object) [
                    'arrangement_id' => 7,
                    'Arrangement' => (object) [
                        'arrangement' => 'Familiedag',
                    ],
                ],
            ];
        }
        if (preg_match('~^([a-z]+)_contactform_1337_arrangements$~', $name)) {
            return [];
        }
        if (preg_match('~^([a-z]+)_contactform_([\d]+)_v2$~', $name)) {
            //TODO
            return (object) [];
        }
        if (preg_match('~^([a-z]+)_contactforms$~', $name)) {
            //TODO
            return (object) [];
        }
        if (preg_match('~^([a-z]+)_products_v2$~', $name)) {
            //TODO
            return (object) [];
        }
        if (preg_match('~^([a-z]+)_voucher_templates$~', $name)) {
            //TODO
            return (object) [];
        }

        throw new Exception('Transient not supported');
    }
}
