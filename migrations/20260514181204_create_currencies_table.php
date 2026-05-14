<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCurrenciesTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Shared trigger function used by all tables to auto-update updated_at
        $this->execute("
            CREATE OR REPLACE FUNCTION set_updated_at()
            RETURNS TRIGGER AS \$\$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql
        ");

        $this->table('currencies', ['id' => true, 'primary_key' => 'id'])
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('code', 'char', ['limit' => 3, 'null' => false])
            ->addColumn('symbol', 'string', ['limit' => 10, 'null' => false])
            ->addIndex(['code'], ['unique' => true])
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();

        $this->execute("
            CREATE TRIGGER trg_currencies_updated_at
            BEFORE UPDATE ON currencies
            FOR EACH ROW EXECUTE FUNCTION set_updated_at()
        ");
    }
}
