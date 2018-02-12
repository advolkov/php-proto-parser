# About
.proto files parser for php

# Installation

# Basic usage
> $Parser = new PhpProtoParser("path/to/some.proto");

- get all messages from proto
    > $all_msg = $Parser->getAllMsg();

- get all enums from proto
    > $all_enum = $Parser->getAllEnum();

- get all params for "message some_msg {}":
    > $some_msg_params = $Parser->getMessageParams("some_msg");

    returns
    ```
     array(1) {
        ["some_msg"] =>
         array(3) {
            ["field_type"] => string("optional/required/etc.")
            ["value_type"] => string("uint32, etc.")
            ["number"] => int(1)
        }
    }
     ```

- get all params for "enum some_enum {}":
    > $some_enum_params = $Parser->getEnumParams("some_enum");

    returns
    ```
    array(3) {
        ["SOME_ENUM_PARAM1"] => int(1)
        ["SOME_ENUM_PARAM2"] => int(2)
        ["SOME_ENUM_PARAM3"] => int(3)
    }
    ```


