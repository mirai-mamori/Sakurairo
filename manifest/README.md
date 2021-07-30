封面图生成工具
===

### 依赖
Python3
PIL (Pillow)

### 安装依赖
安装 Python： <https://docs.python.org/zh-cn/3.9/using/index.html>

安装 PIL：

```bash
# Linix/Mac Terminal
pip install Pillow
# 如果也安装了 Python 2，需要指定 pip 版本：
pip3 install Pillow

# Windows Powershell 或者 CMD
pip install Pillow
# 如果也安装了 Python 2，需要指定 pip 版本：
pip3 install Pillow
# 如果提示权限不足（[WinError 5] Access is denied），请运行：
pip install Pillow --user
```

### 运行
把图片文件放到 `gallary` 目录，Windows 可直接双击 manifest.py，或者和其他操作系统一样，在 Terminal、Powershell、CMD 中运行：

```bash
# 切换到 manifest.py 所在目录：
cd /path/to/manifest/
python manifest.py
# 如果也安装了 Python 2，需要指定 Python 版本：
python3 manifest.py
```

