<?php
    namespace app\framework\Component\http\Model;

    use app\framework\Component\Storage\StorageTrait;
    use app\framework\Component\TemplateEngine\TemplateEngine;

    class Model //implements \ArrayAccess, \JsonSerializable
    {
        use StorageTrait,StdLibTrait;
    }
