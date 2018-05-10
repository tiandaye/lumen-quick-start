<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-08 15:22:27
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-08 15:25:09
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     't_id' => $this->id,
        //     't_name' => $this->name,
        //     't_email' => $this->email,
        //     'lwj' => 123,
        //     // 只有当 $this->isAdmin() 方法返回 true 时， secret 键才会最终在资源响应中被返回。如果该方法返回 false ，则 secret 键将会在资源响应被发送给客户端之前被删除【{tip} 记住，你在资源上调用的方法将被代理到底层模型实例。所以在这种情况下，你调用的 isAdmin 方法实际上是调用最初传递给资源的 Eloquent 模型上的方法。】
        //     'secret' => $this->when($this->isAdmin(), function () {
        //         return 'secret-value';
        //     }),
        //     // 有些时候，你可能希望在给定条件满足时添加多个属性到资源响应里。在这种情况下，你可以使用 mergeWhen 方法在给定的条件为 true 时将多个属性添加到响应中：
        //     $this->mergeWhen($this->isAdmin(), [
        //         'first-secret' => 'value',
        //         'second-secret' => 'value',
        //     ]),
        //     'link' => [
        //         'self' => 'link-value',
        //     ],
        // ];
        // return parent::toArray($request);
        
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];

        // return parent::toArray($request);
    }
}