<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function addDropdown(ItemInterface $item, string $name, array $options)
    {
        $dd = $item->addChild(
            $name,
            ['uri' => "#", 'attributes' => ['class' => 'nav-item dropdown']]
        )
            ->setLinkAttributes([
                'class' => "nav-link dropdown-toggle",
                'href' => '#',
                'data-toggle' => 'dropdown',
            ]);;
        $dd->setChildrenAttribute("class", "dropdown-menu");
        foreach ($options as $name => $route) {
            $dd->addChild(
                $name,
                [
                    'route' => $route,
                    'attributes' => ['class' => 'dropdown-item']
                ]
            );
        }
    }


    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'navbar-nav mr-auto'));

        $menu->addChild('Strains', ['route' => 'strain.index', 'attributes' => ["class" => "nav-item active nav-link"]]);

        $resources = [
            'Plasmids' => 'plasmid.index',
            'Oligos' => 'oligo.index'
        ];
        $this->addDropdown($menu, "Resources", $resources);

        $misc = [
            'Loci' => 'locus.index',
            'Markers' => 'marker.index',
            'Alleles' => 'allele.index',
            // 'Promoters' => 'promoter.index',
            // 'Tags' => 'tags.index'
        ];

        $this->addDropdown($menu, "Misc.", $misc);

        $user = [
            'Log out' => 'app_logout',
        ];
        $this->addDropdown($menu, "User", $user);
        return $menu;
    }

    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('sidebar');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'home']);
        }

        // ... add more children

        return $menu;
    }
}
