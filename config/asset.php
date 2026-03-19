<?php

return array(
    'images' => array(

        'paths' => array(
            'input' => public_path(),
            'output' => public_path().'/uploads/profileimage'
        ),

        'sizes' => array(
            'small' => array(
                'width' => 150,
                'height' => 150
            ),
            'big' => array(
                'width' => 600,
                'height' => 400
            )
        )
    )

);