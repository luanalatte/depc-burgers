<?php

return [

    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'email'                => 'El campo :attribute debe ser una dirección de correo válida.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'mimes'                => 'El tipo de archivo adjuntado no es válido. Se aceptan: :values',
    'min'                  => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file'    => 'El archivo :attribute debe ser de al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
        'array'   => 'El campo :attribute debe tener al menos :min items.',
    ],
    'present'              => 'El campo :attribute debe estar presente en el formulario.',
    'required'             => 'El campo :attribute es obligatorio.',
    'unique'               => 'El campo :attribute ya existe en el sistema.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'txtClave' => [
            'confirmed' => 'Las contraseñas no coinciden.'
        ],
        'txtEmail' => [
            'unique' => 'El correo especificado ya existe en el sistema.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'fileCV' => 'currículum',
        'fileImagen' => 'imagen',
        'txtApellido' => 'apellido',
        'txtClave' => 'contraseña',
        'txtClaveAntigua' => 'contraseña actual',
        'txtDocumento' => 'documento',
        'txtDomicilio' => 'domicilio',
        'txtEmail' => 'email',
        'txtNombre' => 'nombre',
        'txtTelefono' => 'teléfono',
        'lstCategoria' => 'categoría',
        'txtCantidad' => 'cantidad',
        'txtPrecio' => 'precio',
    ],

];
