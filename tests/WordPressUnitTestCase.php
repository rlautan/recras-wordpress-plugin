<?php
namespace Recras;

use mysql_xdevapi\Exception;

class WordPressUnitTestCase extends \WP_UnitTestCase
{
    public function __construct()
    {
        global $recrasPlugin;

        $recrasPlugin->http = $this->createMock(Http::class);

        $recrasPlugin->http->method('get')->will($this->returnCallback([&$this, 'httpGetCallback']));
    }

    public function createPostAndGetContent(string $content): string
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => $content,
        ]);
        return apply_filters('the_content', $post->post_content);
    }

    public function httpGetCallback($_, string $uri)
    {
        switch ($uri) {
            case 'arrangementen':
                return [
                    0 => (object) [
                        'arrangement' => '',
                        'id' => null,
                        'mag_online' => false,
                    ],
                    7 => (object) [
                        'id' => 7,
                        'weergavenaam' => 'Actieve Familiedag',
                        'uitgebreide_omschrijving' => 'Uitgebreide omschrijving van dit arrangement',
                        'programma' => [
                            [
                                'begin' => 'PT0H0M0S',
                                'eind' => 'PT2H15M0S',
                            ]
                        ],
                        'mag_online' => true,
                    ],
                ];
            case 'contactformulieren':
                return [];
            case 'producten':
                return [];
            case 'voucher_templates':
                return [];
        }
        if (preg_match('~contactformulieren/([0-9]+)/arrangementen~', $uri)) {
            return (object) [];
        }
        if (preg_match('~arrangementen/([0-9]+)~', $uri)) {
            return (object) [];
        }
        if (preg_match('~contactformulieren/([0-9]+)/\?embed=Velden~', $uri)) {
            return (object) [];
        }
        throw new Exception('URI not supported');
    }
}
