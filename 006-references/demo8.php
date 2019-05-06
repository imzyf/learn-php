<?php

$catList = [
  '1' => ['id' => 1, 'name' => '颜色', 'parent_id' => 0],
  '2' => ['id' => 2, 'name' => '规格', 'parent_id' => 0],
  '3' => ['id' => 3, 'name' => '白色', 'parent_id' => 1],
  '4' => ['id' => 4, 'name' => '黑色', 'parent_id' => 1],
  '5' => ['id' => 5, 'name' => '大', 'parent_id' => 2],
  '6' => ['id' => 6, 'name' => '小', 'parent_id' => 2],
  '7' => ['id' => 7, 'name' => '黄色', 'parent_id' => 1],
  '8' => ['id' => 8, 'name' => '浅黄色', 'parent_id' => 7],
];

// 改为无效分级

$treeData = []; // 保存结果
foreach ($catList as $item) {
    if (isset($catList[$item['parent_id']]) && !empty($catList[$item['parent_id']])) {// 肯定是子分类 
        $catList[$item['parent_id']]['children'][] = &$catList[$item['id']];
    } else {// 肯定是一级分类
        $treeData[] = &$catList[$item['id']];
    }    
}  
// 否则修改 $catList 将影响 $treeData
unset($catList);
 
var_dump($treeData); 
// xdebug_debug_zval('treeData');
