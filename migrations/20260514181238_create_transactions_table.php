<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTransactionsTable extends AbstractMigration
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
        // Enable pgcrypto for gen_random_uuid() if not already available
        $this->execute('CREATE EXTENSION IF NOT EXISTS pgcrypto');

        $this->table('transactions', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid', ['null' => false, 'default' => 'gen_random_uuid()'])
            ->addColumn('from_account_id', 'integer', ['null' => true])
            ->addColumn('to_account_id', 'integer', ['null' => true])
            ->addColumn('amount', 'biginteger', ['null' => false])
            ->addColumn('currency_id', 'integer', ['null' => false])
            ->addColumn('idempotency_key', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('status', 'string', ['limit' => 20, 'null' => false, 'default' => 'Pending'])
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('from_account_id', 'accounts', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addForeignKey('to_account_id', 'accounts', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addForeignKey('currency_id', 'currencies', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->addIndex(['idempotency_key'], ['unique' => true])
            ->create();

        // Enforce positive amount at the DB level
        $this->execute('ALTER TABLE transactions ADD CONSTRAINT chk_amount_positive CHECK (amount > 0)');

        $this->execute("
            CREATE TRIGGER trg_transactions_updated_at
            BEFORE UPDATE ON transactions
            FOR EACH ROW EXECUTE FUNCTION set_updated_at()
        ");
    }
}
