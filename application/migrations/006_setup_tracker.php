<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Tracker extends CI_Migration {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		/*** TABLES ***/
		// Table structure for table 'tracker_sites'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'site' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'unique'     => TRUE
			),
			'site_class' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE,
				'unique'     => TRUE
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('tracker_sites');

		// Table structure for table 'tracker_titles'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'site_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'title' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE
			),
			'title_url' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => FALSE
			),
			'latest_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'last_updated' => array(
				'type' => 'TIMESTAMP',
				'null' => FALSE,
				//'default'   => 'CURRENT_TIMESTAMP',
				//'on_update' => 'CURRENT_TIMESTAMP' //This is auto-added by CI
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('site_id');
		$this->dbforge->add_key('title');
		$this->dbforge->add_key('title_url');
		$this->dbforge->add_key('latest_chapter');
		$this->dbforge->add_key('last_updated');
		$this->dbforge->create_table('tracker_titles');


		// Table structure for table 'tracker_chapters'
		$this->dbforge->add_field(array(
			'id' => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'title_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
				'null'       => FALSE
				//FOREIGN KEY
			),
			'current_chapter' => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			),
			'last_updated' => array(
				'type' => 'TIMESTAMP',
				'null' => FALSE,
				//'default'   => 'CURRENT_TIMESTAMP',
				//'on_update' => 'CURRENT_TIMESTAMP' //This is auto-added by CI
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('last_updated');
		$this->dbforge->create_table('tracker_chapters');

		/*** Unique/Foreign Keys ***/
		//For whatever reason, dbforge lacks a unique/foreign key function.
		$this->db->query('ALTER TABLE `tracker_titles` ADD UNIQUE INDEX (`site_id`, `title_url`)');
		$this->db->query('ALTER TABLE `tracker_chapters` ADD UNIQUE INDEX (`user_id`, `title_id`)');

		$this->db->query('
			ALTER TABLE `tracker_titles`
			ADD CONSTRAINT `FK_tracker_titles_tracker_sites` FOREIGN KEY (`site_id`) REFERENCES `tracker_sites`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);
		$this->db->query('
			ALTER TABLE `tracker_chapters`
			ADD CONSTRAINT `FK_tracker_chapters_auth_users` FOREIGN KEY (`user_id`) REFERENCES `auth_users`(`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
			ADD CONSTRAINT `FK_tracker_chapters_tracker_titles` FOREIGN KEY (`title_id`) REFERENCES `tracker_titles` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION;'
		);

		/*** TABLE DATA ***/
		// Dumping data for table 'tracker_sites'
		$typesData = array(
			array(
				'id'         => '1',
				'site'       => 'mangafox.me',
				'site_class' => 'MangaFox'
			),
			array(
				'id'         => '2',
				'site'       => 'www.mangahere.co',
				'site_class' => 'MangaHere'
			),
			array(
				'id'         => '3',
				'site'       => 'bato.to',
				'site_class' => 'Batoto'
			)
		);
		$this->db->insert_batch('tracker_sites', $typesData);
	}

	public function down() {
		$this->db->query('SET FOREIGN_KEY_CHECKS=0;');
		$this->dbforge->drop_table('tracker_sites', TRUE);
		$this->dbforge->drop_table('tracker_titles', TRUE);
		$this->dbforge->drop_table('tracker_chapters', TRUE);
		$this->db->query('SET FOREIGN_KEY_CHECKS=1;');
	}
}