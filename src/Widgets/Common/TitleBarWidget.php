<?php

namespace Ceres\Widgets\Common;

use Ceres\Widgets\Helper\BaseWidget;
use Ceres\Widgets\Helper\Factories\WidgetSettingsFactory;
use Ceres\Widgets\Helper\WidgetCategories;
use Ceres\Widgets\Helper\WidgetDataFactory;
use Ceres\Widgets\Helper\WidgetTypes;

class TitleBarWidget extends BaseWidget
{
    protected $template = "Ceres::Widgets.Common.TitleBarWidget";

    public function getData()
    {
        return WidgetDataFactory::make("Ceres::TitleBarWidget")
            ->withLabel("Widget.titleBarLabel")
            ->withPreviewImageUrl("/images/widgets/title-bar.svg")
            ->withType(WidgetTypes::STATIC)
            ->withCategory(WidgetCategories::TEXT)
            ->withPosition(400)
            ->toArray();
    }

    public function getSettings()
    {
        /** @var WidgetSettingsFactory $settings */
        $settings = pluginApp(WidgetSettingsFactory::class);

        $settings->createCustomClass();
        $settings->createAppearance();
        $settings->createSpacing();

        return $settings->toArray();
    }

    protected function getTemplateData($widgetSettings, $isPreview)
    {
        $text = $widgetSettings["content"]["mobile"];
        if ( strlen($widgetSettings["text"]["mobile"]) && !strlen($text) )
        {
            $text = $widgetSettings["text"]["mobile"];
        }
        return [
            "text" => $text
        ];
    }
}
