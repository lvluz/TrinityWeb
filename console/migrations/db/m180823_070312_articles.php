<?php

use yii\db\Migration;

/**
 * Class m180823_070312_articles
 */
class m180823_070312_articles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article_category}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(1024)->notNull(),
            'title' => $this->string(512)->notNull(),
            'parent_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk_article_category_section', '{{%article_category}}', 'parent_id', '{{%article_category}}', 'id', 'cascade', 'cascade');

        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'slug' => $this->string(1024)->notNull(),
            'view' => $this->string(),
            'thumbnail_base_url' => $this->string(1024),
            'thumbnail_path' => $this->string(1024),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'published_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk_article_author', '{{%article}}', 'created_by', '{{%users}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_article_updater', '{{%article}}', 'updated_by', '{{%users}}', 'id', 'set null', 'cascade');
        $this->addForeignKey('fk_article_category', '{{%article}}', 'category_id', '{{%article_category}}', 'id', 'cascade', 'cascade');

        $this->createTable('{{%article_i18n}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'language' => $this->integer()->notNull(),
            'title' => $this->string(512)->notNull(),
            'announce' => $this->string(512)->notNull(),
            'body' => $this->text()->notNull(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->createIndex('idx_language','{{%article_i18n}}','language');

        $this->addForeignKey('fk_article_i18n_to_article', '{{%article_i18n}}', 'article_id', '{{%article}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_article_i18n_to_language', '{{%article_i18n}}', 'language', '{{%language}}', 'ident', 'cascade', 'cascade');

        $this->createTable('{{%article_attachment}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'base_url' => $this->string(),
            'type' => $this->string(),
            'size' => $this->integer(),
            'order' => $this->integer(),
            'name' => $this->string(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk_article_attachment_article', '{{%article_attachment}}', 'article_id', '{{%article}}', 'id', 'cascade', 'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_article_i18n_to_language', '{{%article_i18n}}');
        $this->dropForeignKey('fk_article_i18n_to_article', '{{%article_i18n}}');
        $this->dropForeignKey('fk_article_attachment_article', '{{%article_attachment}}');
        $this->dropForeignKey('fk_article_author', '{{%article}}');
        $this->dropForeignKey('fk_article_updater', '{{%article}}');
        $this->dropForeignKey('fk_article_category', '{{%article}}');
        $this->dropForeignKey('fk_article_category_section', '{{%article_category}}');

        $this->dropTable('{{%article_attachment}}');
        $this->dropTable('{{%article}}');
        $this->dropTable('{{%article_i18n}}');
        $this->dropTable('{{%article_category}}');
    }
}
