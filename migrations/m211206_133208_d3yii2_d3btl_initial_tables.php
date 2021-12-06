<?php

use yii\db\Migration;

class m211206_133208_d3yii2_d3btl_initial_tables  extends Migration {

    public function safeUp() { 
        $this->execute('
            CREATE TABLE `btl_file_data`(  
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `status` ENUM(\'processed\',\'error\', \'deleted\') DEFAULT \'error\',
              `file_data` TEXT,
              `parsed_data` TEXT,
              `add_time` TIMESTAMP,
              `notes` VARCHAR(255),		
              `project_name` VARCHAR(200),		
              `export_datetime` DATETIME,
              PRIMARY KEY (`id`)
            );
                    
        ');

        $this->execute('
                    CREATE TABLE `btl_part`(  
                      `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `file_data_id` INT(10) UNSIGNED NOT NULL,
                      `type` ENUM(\'rawpart\',\'part\'),
                      `single_member_number` INT(10) UNSIGNED,
                      `assembly_number` INT(10) UNSIGNED,
                      `order_number` INT(10) UNSIGNED,
                      `designation` VARCHAR(200),
                      `annotation` VARCHAR(200),
                      `storey` VARCHAR(200),
                      `material` VARCHAR(200),
                      `group` VARCHAR(200),
                      `package` VARCHAR(200),
                      `timber_grade` VARCHAR(200),
                      `quality_grade` VARCHAR(200),
                      `count` INT(5) UNSIGNED,
                      `length` INT(10) UNSIGNED,
                      `height` INT(10) UNSIGNED,
                      `width` INT(10) UNSIGNED,
                      `colour` VARCHAR(200),
                      `uid` INT(10) UNSIGNED,
                      PRIMARY KEY (`id`)
                    );
        ');

        $this->execute('
                        ALTER TABLE `btl_part`
                            ADD CONSTRAINT `fk_file_Data` FOREIGN KEY (`file_data_id`) REFERENCES `btl_file_data`(`id`);
        ');

    }



    public function safeDown() {
        echo "m211206_133208_d3yii2_d3btl_initial_tables cannot be reverted.\n";
        return false;
    }
}
