<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

class PageViewGenerator extends CodeGenerator
{
    /**
     * Generate a full PHP view from a JSON layout
     */
    public function generate(array $schema): array
    {
        $pageName = $schema['page_name'] ?? 'custom_page';
        $layout = $schema['layout'] ?? [];
        
        $fileName = $this->toSnakeCase($pageName) . '.php';
        $content = $this->renderTemplate($layout);
        
        $directory = 'views/pages';
        $path = $directory . '/' . $fileName;
        
        $this->writeFile($path, $content);
        
        return [$path];
    }

    /**
     * Renders the layout components into HTML/PHP
     */
    protected function renderTemplate(array $layout): string
    {
        $html = "<?php\n/** @var string \$title */\n?>\n\n";
        $html .= "<div class=\"custom-page\">\n";
        
        foreach ($layout as $block) {
            $html .= $this->renderBlock($block);
        }
        
        $html .= "</div>\n";
        
        return $html;
    }

    /**
     * Render a single block based on its type
     */
    protected function renderBlock(array $block): string
    {
        $type = $block['type'] ?? 'text';
        $content = $block['content'] ?? '';
        $styles = $block['styles'] ?? [];
        
        $styleStr = $this->arrayToStyles($styles);
        $styleAttr = !empty($styleStr) ? " style=\"$styleStr\"" : "";

        switch ($type) {
            case 'hero':
                return "  <section class=\"hero card glow fade-in\"$styleAttr>\n" .
                       "    <h1>" . htmlspecialchars($content['title'] ?? '') . "</h1>\n" .
                       "    <p class=\"muted\">" . htmlspecialchars($content['subtitle'] ?? '') . "</p>\n" .
                       "  </section>\n";
            
            case 'text':
                return "  <div class=\"content-block\"$styleAttr>\n" .
                       "    <p>" . nl2br(htmlspecialchars($content['text'] ?? '')) . "</p>\n" .
                       "  </div>\n";
                       
            case 'grid':
                $itemsHtml = "";
                foreach ($content['items'] ?? [] as $item) {
                    $itemsHtml .= "    <div class=\"card\">\n" .
                                  "      <h3>" . htmlspecialchars($item['title'] ?? '') . "</h3>\n" .
                                  "      <p class=\"muted\">" . htmlspecialchars($item['body'] ?? '') . "</p>\n" .
                                  "    </div>\n";
                }
                return "  <div class=\"grid " . ($content['columns'] ?? 'three') . "\"$styleAttr>\n$itemsHtml  </div>\n";
            
            case 'image':
                return "  <div class=\"image-block\"$styleAttr>\n" .
                       "    <img src=\"" . htmlspecialchars($content['url'] ?? '') . "\" alt=\"" . htmlspecialchars($content['alt'] ?? '') . "\" style=\"width:100%; border-radius:1rem;\">\n" .
                       "  </div>\n";
            
            case 'cta':
                return "  <div class=\"cta-block\" style=\"text-align: center; margin: 2rem 0;\"$styleAttr>\n" .
                       "    <a href=\"" . htmlspecialchars($content['link'] ?? '#') . "\" class=\"cta\">" . htmlspecialchars($content['label'] ?? 'Click here') . "</a>\n" .
                       "  </div>\n";

            default:
                return "  <!-- Unknown block type: $type -->\n";
        }
    }

    /**
     * Convert array of styles to CSS string
     */
    protected function arrayToStyles(array $styles): string
    {
        $res = [];
        foreach ($styles as $key => $val) {
            $res[] = "$key: $val;";
        }
        return implode(' ', $res);
    }
}
