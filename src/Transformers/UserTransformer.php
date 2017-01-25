<?php

namespace AdiFaidz\Base\Transformers;

use AdiFaidz\Base\Transformers\Transformer;
use AdiFaidz\Base\BaseUser;

class UserTransformer extends Transformer
{
    public function transform($item){
        $roles = [];

        foreach ($item->roles as $role) {
          $roles[] = [
            'id' => $role->id,
            'display_name' => $role->display_name,
          ];
        }

        return [
          'id' => $item->id,
					'name' => $item->name,
					'email' => $item->email,
					'created_at' => $item->created_at,
					'updated_at' => $item->updated_at,
          'roles' => $roles,
        ];
    }
}
