<?php
declare(strict_types=1);

namespace Modules\Settings\Controllers;

use Core\Controller;
use Modules\Settings\Models\Menu;

class MenuController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    public function index(): void
    {
        $menus = Menu::getAll();
        // Enrich menus with parent titles for display
        foreach ($menus as &$m) {
            if ($m['parent_id']) {
                $parent = Menu::find((int)$m['parent_id']);
                $m['parent_title'] = $parent ? $parent['title'] : 'N/A';
            } else {
                $m['parent_title'] = '-';
            }
        }
        $this->renderWithLayout('Settings.Views.menu_index', 'themes.admin.layout', [
            'title' => 'จัดการเมนูนำทาง',
            'menus' => $menus
        ]);
    }

    public function create(): void
    {
        $parents = Menu::getParents();
        $this->renderWithLayout('Settings.Views.menu_create', 'themes.admin.layout', [
            'title' => 'เพิ่มเมนูใหม่',
            'parents' => $parents
        ]);
    }

    public function store(): void
    {
        Menu::create([
            'title' => $_POST['title'],
            'url' => $_POST['url'],
            'icon' => $_POST['icon'],
            'parent_id' => $_POST['parent_id'] ?? null,
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);
        $this->redirect('/settings/menu');
    }

    public function edit(string $id): void
    {
        $menu = Menu::find((int)$id);
        if (!$menu) {
            $this->redirect('/settings/menu');
        }
        $parents = Menu::getParents();
        $this->renderWithLayout('Settings.Views.menu_edit', 'themes.admin.layout', [
            'title' => 'แก้ไขเมนู',
            'menu' => $menu,
            'parents' => $parents
        ]);
    }

    public function update(string $id): void
    {
        Menu::update((int)$id, [
            'title' => $_POST['title'],
            'url' => $_POST['url'],
            'icon' => $_POST['icon'],
            'parent_id' => $_POST['parent_id'] ?? null,
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);
        $this->redirect('/settings/menu');
    }

    public function delete(string $id): void
    {
        Menu::delete((int)$id);
        $this->redirect('/settings/menu');
    }
}
