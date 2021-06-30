<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Join;

class JoinTypes {

    const LEFT_JOIN = 'LEFT JOIN';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const INNER_JOIN = 'INNER JOIN';
    const OUTER_JOIN = 'OUTER JOIN';
    const JOIN = self::LEFT_JOIN;

}