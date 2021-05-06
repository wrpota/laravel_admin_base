<?php


namespace App\Services;

use Illuminate\Support\Arr;

class TreeModelService extends Service
{
    /** @var array */
    private $status = [1, 2];

    /**
     * 设置搜索状态
     *
     * @param $status
     * @return $this
     */
    public function status($status)
    {
        $this->status = is_array($status) ? $status : func_get_args();

        return $this;
    }

    /**
     * 列表
     *
     * @param array $treeLists
     * @param array $checkArr
     * @param bool $check
     * @return array
     */
    public function getList(array $treeLists = [], array $checkArr = [], $check = false)
    {
        $lists = [];
        $treeLists = $treeLists ?: $this->getTreeList();
        foreach ($treeLists as $list) {
            if ($check) {
                $list['checkArr'] = [
                    'type' => 0,
                    'checked' => in_array($list['id'], $checkArr) ? 1 : 0
                ];
            }
            $lists[] = $list;
            if (!empty($list['children'])) {
                $lists = array_merge($lists, $this->getList($list['children'], $checkArr, $check));
            }
        }

        return $lists;
    }

    /**
     * 列表树
     *
     * @param array $ids
     * @return array
     */
    public function getTreeList($ids = [])
    {
        $model = $this->model->whereBetween('status', $this->status);
        if ($ids) {
            $model->whereIn('id', $ids);
        }
        $lists = $model->orderBy('sort')->get()->toArray();

        $lists = array_column($lists, null, 'id');

        return $this->tree($lists);
    }

    /**
     * 组装树
     *
     * @param array $data
     * @param int $pid
     * @return array
     */
    public function tree(array $data, $pid = 0)
    {
        $tree = [];
        foreach ($data as $key => $item) {
            if ($item['pid'] == $pid) {
                $children = $this->tree($data, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }
}
