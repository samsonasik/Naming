<?php

namespace Naming\Spec\Filter;

use Naming\Filter\Naming;

describe('Naming', function (): void {

    describe('filter()', function (): void {

        it('set striptags, trim, and strip double space with ucwords with set lower first, upper after apostrophe and hyphen', function (): void {

            $maps = [
                '<script>Abdul'    => 'Abdul',
                'Abdul  '          => 'Abdul',
                'ABduL'            => 'Abdul',
                'aBDUL m. ikHsan'  => 'Abdul M. Ikhsan',
                'abdul Malik  I'   => 'Abdul Malik I',
                "D'lilah"         => "D'Lilah",
                'äX'               => 'Äx',
                'Veli-matti'       => 'Veli-Matti',
                "d'äx"            => "D'Äx",
                'anna-maria-louise' => 'Anna-Maria-Louise',
            ];

            $naming = new Naming();
            foreach ($maps as $key => $value) {
                expect($naming($key))->toBe($value);
            }

        });

    });

});