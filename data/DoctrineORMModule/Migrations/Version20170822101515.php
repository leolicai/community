<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170822101515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql(
            'INSERT into organization(organization_id, title, members) VALUES (?, ?, ?)',
            ['5a774adc-8723-11e7-9e68-acbc32bf6185', '业委会主任', 1]
        );

        $this->addSql(
            'INSERT into organization(organization_id, title, members) VALUES (?, ?, ?)',
            ['5a775536-8723-11e7-a725-acbc32bf6185', '业委会副主任', 2]
        );

        $this->addSql(
            'INSERT into organization(organization_id, title, members) VALUES (?, ?, ?)',
            ['5a775630-8723-11e7-9e24-acbc32bf6185', '业委会委员', 4]
        );

        $this->addSql(
            'INSERT into organization(organization_id, title, members) VALUES (?, ?, ?)',
            ['5a77570c-8723-11e7-8ac1-acbc32bf6185', '业务会候补委员', 3]
        );

        $this->addSql(
            'INSERT into organization(organization_id, title, members) VALUES (?, ?, ?)',
            ['33068796-8724-11e7-a784-acbc32bf6185', '业委会秘书', 1]
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM organization WHERE 1');
    }
}
