<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030131052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, measure_id INT NOT NULL, recipe_id INT NOT NULL, quantity SMALLINT NOT NULL, INDEX IDX_FEC530A9933FE08C (ingredient_id), INDEX IDX_FEC530A95DA37D00 (measure_id), INDEX IDX_FEC530A959D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_recipe (member_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_FE5E33047597D3FE (member_id), INDEX IDX_FE5E330459D8A214 (recipe_id), PRIMARY KEY(member_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vegetable_month (vegetable_id INT NOT NULL, month_id INT NOT NULL, INDEX IDX_D1B747713D33F4D6 (vegetable_id), INDEX IDX_D1B74771A0CBDE4 (month_id), PRIMARY KEY(vegetable_id, month_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A95DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE member_recipe ADD CONSTRAINT FK_FE5E33047597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_recipe ADD CONSTRAINT FK_FE5E330459D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vegetable_month ADD CONSTRAINT FK_D1B747713D33F4D6 FOREIGN KEY (vegetable_id) REFERENCES vegetable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vegetable_month ADD CONSTRAINT FK_D1B74771A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE botanical ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE genre ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ingredient ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE meal ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE measure ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE member ADD user_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA78A76ED395 ON member (user_id)');
        $this->addSql('ALTER TABLE month ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE recipe ADD meal_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137639666D6 ON recipe (meal_id)');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vegetable ADD botanical_id INT DEFAULT NULL, ADD genre_id INT DEFAULT NULL, ADD ingredient_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vegetable ADD CONSTRAINT FK_DB9894F7D1A72A2C FOREIGN KEY (botanical_id) REFERENCES botanical (id)');
        $this->addSql('ALTER TABLE vegetable ADD CONSTRAINT FK_DB9894F74296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE vegetable ADD CONSTRAINT FK_DB9894F7933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_DB9894F7D1A72A2C ON vegetable (botanical_id)');
        $this->addSql('CREATE INDEX IDX_DB9894F74296D31F ON vegetable (genre_id)');
        $this->addSql('CREATE INDEX IDX_DB9894F7933FE08C ON vegetable (ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9933FE08C');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A95DA37D00');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A959D8A214');
        $this->addSql('ALTER TABLE member_recipe DROP FOREIGN KEY FK_FE5E33047597D3FE');
        $this->addSql('ALTER TABLE member_recipe DROP FOREIGN KEY FK_FE5E330459D8A214');
        $this->addSql('ALTER TABLE vegetable_month DROP FOREIGN KEY FK_D1B747713D33F4D6');
        $this->addSql('ALTER TABLE vegetable_month DROP FOREIGN KEY FK_D1B74771A0CBDE4');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE member_recipe');
        $this->addSql('DROP TABLE vegetable_month');
        $this->addSql('ALTER TABLE measure DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78A76ED395');
        $this->addSql('DROP INDEX UNIQ_70E4FA78A76ED395 ON member');
        $this->addSql('ALTER TABLE member DROP user_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137639666D6');
        $this->addSql('DROP INDEX IDX_DA88B137639666D6 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP meal_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE botanical DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE month DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE genre DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE ingredient DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE vegetable DROP FOREIGN KEY FK_DB9894F7D1A72A2C');
        $this->addSql('ALTER TABLE vegetable DROP FOREIGN KEY FK_DB9894F74296D31F');
        $this->addSql('ALTER TABLE vegetable DROP FOREIGN KEY FK_DB9894F7933FE08C');
        $this->addSql('DROP INDEX IDX_DB9894F7D1A72A2C ON vegetable');
        $this->addSql('DROP INDEX IDX_DB9894F74296D31F ON vegetable');
        $this->addSql('DROP INDEX IDX_DB9894F7933FE08C ON vegetable');
        $this->addSql('ALTER TABLE vegetable DROP botanical_id, DROP genre_id, DROP ingredient_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE meal DROP created_at, DROP updated_at');
    }
}
