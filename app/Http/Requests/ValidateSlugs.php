<?php

namespace App\Http\Requests;


trait ValidateSlugs
{
    /**
     * @param array|null $keys
     *
     * @return mixed
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $slugs = $this->slugs();

        foreach ($slugs as $slug)
        {
            if (!empty($this->route($slug))) { $data[$slug] = $this->route($slug); }
        }

        return $data;
    }

    public function slugs()
    {
        return [];
    }
}