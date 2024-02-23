<?php

use yii\db\Migration;

/**
 * Class m240223_131002_fix_file_foreign_key_in_tertimonial_table
 */
class m240223_131002_fix_file_foreign_key_in_tertimonial_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            '{{%fk-testimonial-customer_image_id}}',
            '{{%testimonial}}'
        );

        $this->alterColumn('{{%testimonial}}', 'customer_image_id', $this->integer());

        // add foreign key for table `{{%file}}`
        $this->addForeignKey(
            '{{%fk-testimonial-customer_image_id}}',
            '{{%testimonial}}',
            'customer_image_id',
            '{{%file}}',
            'id',
            'SET NULL' //alteração de CASCADE para SET NULL, comparado com a migration testimonial_table
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-testimonial-customer_image_id}}',
            '{{%testimonial}}'
        );
        $this->alterColumn('{{%testimonial}}', 'customer_image_id', $this->integer()->notNull());

        // add foreign key for table `{{%file}}`
        $this->addForeignKey(
            '{{%fk-testimonial-customer_image_id}}',
            '{{%testimonial}}',
            'customer_image_id',
            '{{%file}}',
            'id',
            'CASCADE' 
        );
    }
}
