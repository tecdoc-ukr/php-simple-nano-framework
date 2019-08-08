<?php
$CNF_head_title = 'Main Page';  // title текущей веб страницы

/**************************************************************
Формируем данные для View
**************************************************************/
$part_num_length = $CNF_request['part_num']['lenth'];
$part_num = $INX_request->get('part_num', $CNF_part_num_def);
