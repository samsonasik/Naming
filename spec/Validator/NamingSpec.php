<?php

namespace Naming\Spec\Validator;

use Naming\Validator\Naming;

describe('Naming', function () {

    given('validator', function () {

       return new Naming();

    });

    describe('->isValid', function () {

        it('returns false for incorrect naming', function () {

            $namings = [
                // include special character(s)
                '~~',
                'abdul%',
                'abdul$x',
                'abdul___',
                'abdul?',

                // include number
                'abdul1',

                // dot not in the last word
                '..Foo',
                'Foo. .',
                'm.m.m',

                // single strlen($value) === 1 condition
                '.',
                '-',
                '\'',

                // consecutive value == -,',.
                '--',
                '\'\'',
                '...',
                'Foo.....',
            ];

            foreach ($namings as $naming) {
                $actual = $this->validator->isValid($naming);
                expect($actual)->toBeFalsy();
            }

        });

        it('returns true for correct naming', function () {

            $namings = [
                'abdul malik ikhsan',
                'abdul m. ikhsan',
                'abdul',
                'M.',
                'D\'Lilah',
                'Veli-Matti',
                'Setälä',
            ];

            foreach ($namings as $naming) {
                $actual = $this->validator->isValid($naming);
                expect($actual)->toBeTruthy();
            }

        });

    });

});
