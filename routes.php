array (
  'GET' => 
  array (
    'user' => 
    array (
      '..' => 
      array (
        '::' => 
        array (
          'regex' => '[a-z]+',
        ),
        'article' => 
        array (
          '..' => 
          array (
            '::' => 
            array (
              'regex' => '[0-9]+',
              'events' => 
              array (
                'before' => 
                array (
                ),
                'after' => 
                array (
                ),
              ),
              'handle' => 
              Closure::__set_state(array(
              )),
            ),
          ),
        ),
      ),
    ),
    'box' => 
    array (
      'release' => 
      array (
        'surplus' => 
        array (
          '::' => 
          array (
            'events' => 
            array (
              'before' => 
              array (
              ),
              'after' => 
              array (
              ),
            ),
            'handle' => 
            Closure::__set_state(array(
            )),
          ),
        ),
      ),
    ),
  ),
  'OPTIONS' => 
  array (
    'user' => 
    array (
      '..' => 
      array (
        '::' => 
        array (
          'regex' => '[a-z]+',
        ),
        'article' => 
        array (
          '..' => 
          array (
            '::' => 
            array (
              'regex' => '[0-9]+',
              'events' => 
              array (
              ),
              'handle' => 
              Closure::__set_state(array(
              )),
            ),
          ),
        ),
      ),
    ),
    'box' => 
    array (
      'release' => 
      array (
        'surplus' => 
        array (
          '::' => 
          array (
            'events' => 
            array (
            ),
            'handle' => 
            Closure::__set_state(array(
            )),
          ),
        ),
      ),
    ),
  ),
)