<?php

return array(
    'images' => array(

        'paths' => array(
            'getimage' => public_path().'/uploads',
            'output' => public_path().'/uploads/thumb'
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