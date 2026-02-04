<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Produk_model extends CI_Model {

    // Cek Kategori (hindari duplikat)
    public function get_or_create_kategori($nama_kategori) {
        $q = $this->db->get_where('kategori', ['nama_kategori' => $nama_kategori]);
        if($q->num_rows() > 0){
            return $q->row()->id_kategori;
        } else {
            $this->db->insert('kategori', ['nama_kategori' => $nama_kategori]);
            return $this->db->insert_id();
        }
    }

    // Cek Status (hindari duplikat)
    public function get_or_create_status($nama_status) {
        $q = $this->db->get_where('status', ['nama_status' => $nama_status]);
        if($q->num_rows() > 0){
            return $q->row()->id_status;
        } else {
            $this->db->insert('status', ['nama_status' => $nama_status]);
            return $this->db->insert_id();
        }
    }

    // Simpan/Update Produk dari API
    public function upsert_produk($data) {
        $this->db->where('id_produk', $data['id_produk']);
        $q = $this->db->get('produk');

        if ($q->num_rows() > 0) {
            $this->db->where('id_produk', $data['id_produk']);
            $this->db->update('produk', $data);
        } else {
            $this->db->insert('produk', $data);
        }
    }

    // Ambil Data
    public function get_all_produk($status_filter = null) {
        $this->db->select('produk.*, kategori.nama_kategori, status.nama_status');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.kategori_id = kategori.id_kategori', 'left');
        $this->db->join('status', 'produk.status_id = status.id_status', 'left');

        if ($status_filter) {
            $this->db->where('status.nama_status', $status_filter);
        }
        
        $this->db->order_by('id_produk', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('produk', ['id_produk' => $id])->row();
    }
    
    public function insert($data) { return $this->db->insert('produk', $data); }
    public function update($id, $data) { $this->db->where('id_produk', $id); return $this->db->update('produk', $data); }
    public function delete($id) { $this->db->where('id_produk', $id); return $this->db->delete('produk'); }
}