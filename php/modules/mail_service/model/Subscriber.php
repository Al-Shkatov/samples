<?php

class SubscriberModel extends Model
{
    private $offset = 0;
    private $perpage = 20;
    protected $_name = 'subscriber';

    public function filter($data = array())
    {
        isset($data['limit']) &&
        $this->limit(
            isset($data['limit']['page']) ? ($data['limit']['page'] * $this->perpage) : $this->offset,
            isset($data['limit']['perpage']) ? $data['limit']['perpage'] : $this->perpage);
        if (isset($data['where']) && is_array($data['where'])) {
            foreach ($data['where'] as $wfield => $wvalue) {
                if (is_numeric($wfield)) {
                    $this->where($wvalue);
                } else {
                    $this->where($wfield, $wvalue);
                }
            }
        }
        return $this;
    }

    public function all()
    {
        return $this->select()->fetch();
    }

    public function get($id)
    {
        return $this->where('id', $id)->fetch(Model::FETCH_ROW);
    }

    public function store($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->save($data);
    }
    public function upd($data){
        return $this->save($data);
    }
}