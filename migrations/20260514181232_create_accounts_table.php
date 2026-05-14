<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAccountsTable extends AbstractMigration
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
        $this->table('accounts', ['id' => true, 'primary_key' => 'id'])
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('balance', 'biginteger', ['null' => false, 'default' => 0])
            ->addColumn('currency_id', 'integer', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 20, 'null' => false, 'default' => 'Active'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addForeignKey('currency_id', 'currencies', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();

        $this->execute("
            CREATE TRIGGER trg_accounts_updated_at
            BEFORE UPDATE ON accounts
            FOR EACH ROW EXECUTE FUNCTION set_updated_at()
        ");
    }
}
