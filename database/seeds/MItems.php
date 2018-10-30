<?php

use Illuminate\Database\Seeder;

class MItems extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedWorkStatusName();
    }

    /**
     * Seed db
     */
    public function seedWorkStatusName()
    {
        $categoryName = '作業状態';
        $listStatus = [
            0 => '電話対応待ち',
            1 => '作業開始済',
            2 => '作業中',
            3 => '作業終了'
        ];
        $insertData = [];
        foreach ($listStatus as $key => $item) {
            $insertData[] = [
                'item_id' => $key,
                'item_category' => $categoryName,
                'item_name' => $item,
                'sort_order' => $key,
                'modified_user_id' =>'SYSTEM',
                'modified' => date('Y-m-d H:i:s'),
                'created_user_id' => 'SYSTEM',
                'created' => date('Y-m-d H:i:s')
            ];
        }
        DB::table('m_items')->where(['item_category' => $categoryName])->delete();
        DB::table('m_items')->insert($insertData);
    }
}
