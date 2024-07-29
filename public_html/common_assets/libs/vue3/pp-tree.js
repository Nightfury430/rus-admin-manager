((window) => {
    const { ref, shallowRef, watch, nextTick, onMounted } = window.Vue;
    const { useVirtualList } = window.VueUse;

    window.PpTree = {
        props: {
            nodes: Array,
            maxHeight: Number,
            dark: Boolean,
            itemHeight: Number,
            autoMaxHeight: Boolean,
        },

        emits: ["selected", "updated"],

        setup(props, { emit }) {
            const treeRef = ref(null);
            const nodes = shallowRef([]);
            const slickList = shallowRef([]);
            const itemHeight = props.itemHeight || 30;
            const lineInput = ref(0);
            const selectedId = ref(null);

            const treeStyles = shallowRef({
                "--pp-tree-max-height": `${props.maxHeight || 500}px`,
                "--pp-tree-item-height": `${itemHeight}px`,
            });

            let newIdCounter = 1;

            let dragState = {
                realStartId: null,
                isDragging: false,
                inactiveIndex: null,
                inactiveLength: 1,
            };

            const { list, scrollTo, containerProps, wrapperProps } = useVirtualList(
                nodes, { itemHeight: itemHeight },
            );

            if (props.autoMaxHeight) {
                onMounted(() => {
                    const el = treeRef.value;

                    if (!el) {
                        return;
                    }

                    const doc = document.documentElement;
                    const height = doc.clientHeight - (el.getBoundingClientRect().top + window.scrollY) - 70;
                    let maxHeight = Math.max(props.maxHeight || 500, height);
                    maxHeight = Math.min(maxHeight, 30 * itemHeight);

                    treeStyles.value = {
                        "--pp-tree-max-height": `${maxHeight}px`,
                        "--pp-tree-item-height": `${itemHeight}px`,
                    };
                });
            }

            watch(() => props.nodes, (newNodes) => {
                nodes.value = prepareNodesData(newNodes);
                scrollToIndex(0);
            });

            watch(list, (newList) => {
                if (newList.length > 100) {
                    return;
                }

                if (newList.length === 0) {
                    scrollToIndex(nodes.value.length - 1);
                }

                updateDragState(newList);
                slickList.value = newList;
            }, { immediate: true, flush: "sync" });

            nextTick(() => {
                nodes.value = prepareNodesData(props.nodes);
            });

            watch(selectedId, (selectedId) => {
                emit("selected", selectedId);
            });

            const api = {
                move: (oldIndex, newIndex, inside) => move(nodes, oldIndex, newIndex, inside, false),
                add: (index, node, inside) => add(nodes, index, node, inside, false),
                remove: (index) => remove(nodes, index, false),
                collapse: (parentIndex) => hideChildren(nodes, parentIndex, null, true),
                expand: (parentIndex) => showChildren(nodes, parentIndex, true),
                expandAll,
                collapseAll,
                removeAll: () => removeAll(false),
                toggleExpand,
                findLastChildIndex: (nodesValue, parentIndex) => findLastChildIndex(nodesValue || nodes.value, parentIndex),
                getDataIdAttribute,
                checkNodesIndex,
                findIndexById,
                calcRelativeIndex,
                getNode,
                updateNode,
                findNodeById,
                findParentByNodeId,
                getDirectChildren,
                getSelectedId,
                setSelected,
                scrollToIndex,
                scrollToId,
                walkNodeTree: (nodeStartIndex, fn) => walkNodeTree(nodes, nodeStartIndex, fn),
                getFlatTree,
                getLastIndex,
                getLastRelativeIndex,
                newId,
                updateTree,
            };

            function prepareNodesData(nodes) {
                const expandChildrenInChildren = (parentNode) => {
                    if (!parentNode) {
                        return;
                    }

                    const parents = [parentNode];
                    let currentParent;

                    while (currentParent = parents.pop()) {
                        const nodes = currentParent.children;

                        if (!nodes || !nodes.length) {
                            continue;
                        }

                        let nodesLength = nodes.length;
                        let i = 0;

                        while (i < nodesLength) {
                            const node = nodes[i];

                            if (node.isLeaf) {
                                i += 1;
                                continue;
                            }

                            if (node.expanded) {
                                nodes.splice(i + 1, 0, ...node.children);
                                node.children = null;
                                nodesLength = nodes.length;
                            }
                            else {
                                parents.push(node);
                            }

                            i += 1;
                        }
                    }
                }

                let parents = [];

                const prepareNode = (node) => {
                    if (node.preventChildren && node.hasOwnProperty("children")) {
                        node.children = null;
                    }

                    if (!node.hasOwnProperty("expanded")) {
                        node.expanded = true;
                    }

                    node.isLeaf = !(node.children && node.children.length);

                    if (!node.isLeaf) {
                        parents.push(node);
                    }
                }

                let nodesLength = nodes.length;
                let i = 0;

                while (i < nodesLength) {
                    const node = nodes[i];
                    node.level = 0;
                    prepareNode(node);

                    let parent;

                    while (parent = parents.pop()) {
                        const childrenLevel = parent.level + 1;

                        for (const child of parent.children) {
                            child.level = childrenLevel;
                            prepareNode(child);
                        }
                    }

                    i += 1;
                }

                i = 0;

                while (i < nodesLength) {
                    const node = nodes[i];

                    if (node.isLeaf) {
                        i += 1;
                        continue;
                    }

                    if (node.expanded) {
                        nodes.splice(i + 1, 0, ...node.children);
                        node.children = null;
                        nodesLength = nodes.length;
                    }
                    else {
                        expandChildrenInChildren(node);
                    }

                    i += 1;
                }

                return nodes;
            }

            function sortStart({ event, index, node }) {
                startDragState(index);
                selectedId.value = dragState.realStartId;
            }

            function sortEnd({ event, newIndex, oldIndex, inside }) {
                const startIndex = findIndexById(dragState.realStartId);
                const endIndex = findIndexById(getRealId(newIndex));

                endDragState();

                if (startIndex === null || endIndex === null) {
                    return;
                }

                move(nodes, startIndex, endIndex, inside, true);
            }

            function sortCancel({ event, newIndex, oldIndex }) {
                endDragState();
            }

            function startDragState(virtualListIndex) {
                dragState.realStartId = getRealId(virtualListIndex);
                dragState.inactiveIndex = getNodeIndex(virtualListIndex);
                const lastChildIndex = findLastChildIndex(nodes.value, dragState.inactiveIndex);
                dragState.inactiveLength = Math.max(1, lastChildIndex - dragState.inactiveIndex + 1);
                dragState.isDragging = true;

                updateDragState(list.value);
            }

            function updateDragState(listValue) {
                if (!(dragState.isDragging && dragState.inactiveIndex >= 0)) {
                    return;
                }

                const from = dragState.inactiveIndex;
                const to = from + dragState.inactiveLength - 1;

                for (const item of listValue) {
                    item.data.inactive = item.data.preventChildren || (item.index >= from && item.index <= to);
                }
            }

            function endDragState() {
                dragState.isDragging = false;
                dragState.realStartId = null;
                dragState.inactiveIndex = null;
                dragState.inactiveLength = 1;

                for (const item of list.value) {
                    item.data.inactive = false;
                }

                for (const item of nodes.value) {
                    item.inactive = false;
                }
            }

            function move(nodes, oldIndex, newIndex, inside, emitEvent) {
                if (oldIndex === newIndex) {
                    return;
                }

                let oldLastChildIndex = findLastChildIndex(nodes.value, oldIndex);

                const noChildren = oldLastChildIndex < 0;

                if (!noChildren && newIndex >= oldIndex && newIndex <= oldLastChildIndex) {
                    return;
                }

                const oldRelative = calcRelativeIndex(oldIndex);

                const eventData = {
                    type: "move",
                    node: nodes.value[oldIndex],
                    oldIndex: oldIndex,
                    newIndex: null,
                    oldRIndex: oldRelative.rIndex,
                    newRIndex: null,
                    oldParent: oldRelative.parent,
                    newParent: null,
                }

                let newLevel = null;

                if (inside) {
                    const node = nodes.value[newIndex];

                    if (node.preventChildren) {
                        return;
                    }

                    if (node.isLeaf) {
                        node.isLeaf = false;
                        node.expanded = true;
                    }

                    const notExpanded = !node.expanded;

                    if (!node.expanded) {
                        showChildren(nodes, newIndex, false);
                    }

                    const newLastChildIndex = findLastChildIndex(nodes.value, newIndex);

                    if (notExpanded && oldIndex > newIndex && newLastChildIndex > 0) {
                        const oldOffset = newLastChildIndex - newIndex;

                        oldIndex += oldOffset;

                        if (oldLastChildIndex >= 0) {
                            oldLastChildIndex += oldOffset;
                        }
                    }

                    newLevel = node.level + 1;
                    newIndex = newLastChildIndex < 0 ? newIndex + 1 : newLastChildIndex + 1;
                }
                else if (noChildren && newIndex - oldIndex === 1 && newIndex < nodes.value.length) {
                    newLevel = nodes.value[newIndex].level;
                }

                updateLevel(nodes, oldIndex, newIndex, newLevel, oldLastChildIndex);

                const cutValues = nodes.value.splice(oldIndex, noChildren ? 1 : oldLastChildIndex - oldIndex + 1);
                newIndex = newIndex - (oldIndex < newIndex ? cutValues.length : 0);
                nodes.value.splice(newIndex, 0, ...cutValues);

                eventData.newIndex = newIndex;

                const oldPrevIndex = (oldIndex <= newIndex) ?
                    oldIndex - 1 :
                    oldIndex + cutValues.length - 1;

                updateIsLeaf(nodes, oldPrevIndex);

                const newRelative = calcRelativeIndex(eventData.newIndex);
                eventData.newRIndex = newRelative.rIndex;
                eventData.newParent = newRelative.parent;

                nodes.value = [...nodes.value];

                if (emitEvent) {
                    emit("updated", eventData);
                }

                return eventData;
            }

            function add(nodes, index, node, inside = false, emitEvent = true) {
                if (inside) {
                    return addToParent(nodes, index, node, emitEvent);
                }

                if (!node || (node.id !== 0 && !node.id)) {
                    throw new Error("Can't add node without id!");
                }

                const nodesLength = nodes.value.length;
                const toEnd = index === nodesLength;

                if (!toEnd && !checkNodesIndex(index)) {
                    return;
                }

                selectedId.value = node.id;

                node.isLeaf = true;
                node.expanded = true;
                node.level = (index > 0 && !toEnd) ? nodes.value[index - 1].level : 0;

                nodes.value.splice(index, 0, node);

                let parentIndex = null;

                if (node.level !== 0) {
                    for (let i = index - 1; i >= 0; i--) {
                        if (nodes.value[i].level < node.level) {
                            parentIndex = i;
                            break;
                        }
                    }
                }

                const parent = parentIndex ? nodes.value[parentIndex] : null;

                nodes.value = [...nodes.value];

                const eventData = { type: "add", node, index, parent, parentIndex };

                if (emitEvent) {
                    emit("updated", eventData);
                }

                return eventData;
            }

            function addToParent(nodes, parentIndex, node, emitEvent) {
                if (!node || (node.id !== 0 && !node.id)) {
                    throw new Error("Can't add node without id!");
                }

                if (!checkNodesIndex(parentIndex)) {
                    return;
                }

                const parent = nodes.value[parentIndex];

                if (parent.preventChildren) {
                    return;
                }

                selectedId.value = node.id;

                node.level = parent.level + 1;

                if (!parent.isLeaf && !parent.expanded) {
                    showChildren(nodes, parentIndex, false);
                }

                const lastChildIndex = findLastChildIndex(nodes.value, parentIndex);
                const insertIndex = lastChildIndex < 0 ? parentIndex + 1 : lastChildIndex + 1;

                nodes.value.splice(insertIndex, 0, node);
                updateIsLeaf(nodes, insertIndex);
                updateIsLeaf(nodes, parentIndex);

                nodes.value = [...nodes.value];

                const eventData = { type: "add", node, index: insertIndex, parent, parentIndex };

                if (emitEvent) {
                    emit("updated", eventData);
                }

                return eventData;
            }

            function remove(nodes, index, emitEvent) {
                if (!checkNodesIndex(index)) {
                    return;
                }

                selectedId.value = null;

                const node = nodes.value[index];

                if (!node.isLeaf && node.expanded) {
                    hideChildren(nodes, index, null, false);
                }

                let childrenIds = [];

                walkNodeTree(nodes, index, (x) => {
                    if (node === x) {
                        return;
                    }

                    childrenIds.push(x.id);
                });

                if (childrenIds.length === 0) {
                    childrenIds = null;
                }

                nodes.value.splice(index, 1);
                updateIsLeaf(nodes, index - 1);

                nodes.value = [...nodes.value];

                const eventData = { type: "remove", node, index, childrenIds };

                if (emitEvent) {
                    emit("updated", eventData);
                }

                return eventData;
            }

            function hideChildren(nodes, parentIndex, lastChildIndex = null, updateNodes = true) {
                const parentNode = nodes.value[parentIndex];

                const length = (lastChildIndex !== null
                    ? lastChildIndex
                    : findLastChildIndex(nodes.value, parentIndex)) - parentIndex;

                parentNode.children = (length > 0) ?
                    nodes.value.splice(parentIndex + 1, length) :
                    null;

                if (length > 0) {
                    if (updateNodes) {
                        nodes.value = [...nodes.value];
                    }
                }

                parentNode.expanded = false;
            }

            function showChildren(nodes, parentIndex, updateNodes = true) {
                const parentNode = nodes.value[parentIndex];
                const children = parentNode.children;

                if (children && children.length) {
                    nodes.value.splice(parentIndex + 1, 0, ...parentNode.children);

                    if (updateNodes) {
                        nodes.value = [...nodes.value];
                    }
                }

                parentNode.children = null;
                parentNode.expanded = true;
            }

            function expandAll() {
                let nodesLength = nodes.value.length;

                let i = 0;

                while (i < nodesLength) {
                    if (!nodes.value[i].isLeaf) {
                        showChildren(nodes, i, false);
                        nodesLength = nodes.value.length;
                    }

                    i += 1;
                }

                nodes.value = [...nodes.value];
            }

            function collapseAll() {
                const hideChildrenInChildren = (parentNode) => {
                    if (!parentNode) {
                        return;
                    }

                    const parents = [parentNode];
                    let currentParent;

                    while (currentParent = parents.pop()) {
                        const nodes = currentParent.children;

                        if (!nodes || !nodes.length) {
                            currentParent.expanded = false;
                            continue;
                        }

                        const nodesLength = nodes.length;
                        let i = nodesLength - 1;

                        while (i >= 0) {
                            const node = nodes[i];

                            if (node.isLeaf) {
                                i -= 1;
                                continue;
                            }

                            if (node.expanded) {
                                const parentIndex = i;

                                const length = findLastChildIndex(nodes, parentIndex) - parentIndex;

                                nodes[parentIndex].children = (length > 0) ?
                                    nodes.splice(parentIndex + 1, length) :
                                    null;

                                nodes[parentIndex].expanded = false;
                            }
                            else {
                                parents.push(node);
                            }

                            i -= 1;
                        }
                    }
                }

                const nodesLength = nodes.value.length;

                let i = nodesLength - 1;

                while (i >= 0) {
                    const node = nodes.value[i];

                    if (!node.isLeaf) {
                        if (node.expanded) {
                            hideChildren(nodes, i, null, false);
                        }
                        else {
                            hideChildrenInChildren(node);
                        }
                    }

                    i -= 1;
                }

                nodes.value = [...nodes.value];
            }

            function removeAll(emitEvent) {
                selectedId.value = null;
                nodes.value = [];

                if (emitEvent) {
                    emit("updated", { type: "remove-all" });
                }
            }

            function toggleExpand(index) {
                const val = nodes;
                const node = val.value[index];

                if (node.isLeaf) {
                    return;
                }

                if (node.expanded) {
                    hideChildren(val, index);
                    return;
                }

                showChildren(val, index);
            }

            function updateLevel(nodes, oldIndex, newIndex, newLevel, oldLastChildIndex) {
                const node = nodes.value[oldIndex];

                let level = 0;

                if (newLevel || newLevel === 0) {
                    level = newLevel;
                }
                else if (newIndex > 0) {
                    let prevNewIndexAfterCut = newIndex - 1 === oldLastChildIndex ? oldIndex - 1 : newIndex - 1;

                    const topLevel = nodes.value[prevNewIndexAfterCut].level;
                    let bottomLevel = Math.min(topLevel + 1, nodes.value[newIndex].level);

                    level = Math.max(topLevel, bottomLevel);
                }

                const levelDelta = level - node.level;

                if (levelDelta === 0) {
                    return;
                }

                walkNodeTree(nodes, oldIndex, (node) => node.level += levelDelta);
            }

            function updateIsLeaf(nodes, index) {
                if (!checkNodesIndex(index)) {
                    return;
                }

                const node = nodes.value[index];

                if (node.children && node.children.length) {
                    node.isLeaf = false;
                    node.expanded = false;
                    return;
                }

                const nextNodeLevel = (index + 1 < nodes.value.length) ?
                    nodes.value[index + 1].level :
                    node.level;

                if (node.level < nextNodeLevel) {
                    node.isLeaf = false;
                    node.expanded = true;
                    return;
                }

                node.isLeaf = true;
                node.expanded = true;
            }

            function addItemHandler(e) {
                if (!e || !e.target) {
                    return;
                }

                const id = getDataIdAttribute(e.target);

                if (!id) {
                    return;
                }

                const index = findIndexById(id);

                const node = {
                    id: newId(),
                    title: "New item",
                };

                if (!checkNodesIndex(index)) {
                    add(nodes, nodes.value.length - 1, node, false, true);
                    return;
                }

                add(nodes, index, node, true, true);
            }

            function removeItemHandler(e) {
                if (!e || !e.target) {
                    return;
                }

                const id = getDataIdAttribute(e.target);

                if (!id) {
                    return;
                }

                const index = findIndexById(id);
                remove(nodes, index, true);
            }

            function selectHandler(e) {
                if (!e || !e.target) {
                    return;
                }

                if (e.target && (e.target.tagName === "BUTTON" || e.target.classList.contains("pp-tree-drag"))) {
                    return;
                }

                const id = getDataIdAttribute(e.target);

                if (!id) {
                    return;
                }

                if (equalIds(selectedId.value, id)) {
                    selectedId.value = null;
                    return;
                }

                selectedId.value = id;
            }

            function findLastChildIndex(nodesValue, parentIndex) {
                const parentNode = nodesValue[parentIndex];

                const parentLevel = parentNode.level;

                let foundIndex = -1;

                const nodesLength = nodesValue.length;

                if (nodesLength === 0) {
                    return -1;
                }

                if (parentIndex === nodesLength - 1) {
                    return -1;
                }

                for (let i = parentIndex + 1; i < nodesLength; i++) {
                    if (nodesValue[i].level <= parentLevel) {
                        foundIndex = i - 1;
                        break;
                    }
                }

                if (foundIndex < 0) {
                    foundIndex = nodesLength - 1;
                }

                if (foundIndex <= parentIndex) {
                    foundIndex = -1;
                }

                return foundIndex;
            }

            function getDataIdAttribute(el) {
                const itemElement = el.closest(".pp-tree-item");

                if (!itemElement) {
                    return null;
                }

                const id = itemElement.getAttribute("data-id");

                if (!id) {
                    return null;
                }

                return id;
            }

            function checkNodesIndex(index) {
                if (index < 0 || index >= nodes.value.length) {
                    return false;
                }

                if (index !== 0 && !index) {
                    return false;
                }

                return true;
            }

            function getNodeIndex(virtualListIndex) {
                let id;

                try {
                    id = list.value[virtualListIndex].index;
                } catch { }

                if (id !== 0 && !id) {
                    return null;
                }

                return id;
            }

            function getRealId(virtualListIndex) {
                let id;

                try {
                    id = list.value[virtualListIndex].data.id;
                } catch { }

                if (id !== 0 && !id) {
                    return null;
                }

                return id;
            }

            function calcRelativeIndex(index) {
                if (!checkNodesIndex(index)) {
                    return { rIndex: null, parent: null };
                }

                let rIndex = 0;
                let parent = null;
                const level = nodes.value[index].level;

                for (let i = index - 1; i >= 0; i--) {
                    const currentLevel = nodes.value[i].level;

                    if (currentLevel < level) {
                        parent = nodes.value[i];
                        break;
                    }

                    if (currentLevel === level) {
                        rIndex += 1;
                    }
                }

                return { rIndex, parent };
            }

            function findIndexById(realId) {
                if (realId !== 0 && !realId) {
                    return null;
                }

                let id;

                try {
                    id = nodes.value.findIndex((x) => equalIds(x.id, realId));
                } catch { }

                if (id !== 0 && !id) {
                    return null;
                }

                return id;
            }

            function findVirtualIndexById(realId) {
                if (realId !== 0 && !realId) {
                    return null;
                }

                let id;

                try {
                    id = list.value.findIndex((x) => equalIds(x.data.id, realId));
                } catch { }

                if (id !== 0 && !id) {
                    return null;
                }

                return id;
            }

            function getNode(index) {
                if (!checkNodesIndex(index)) {
                    return null;
                }

                return nodes.value[index];
            }

            function updateNode(index, updatedNode) {
                if (!checkNodesIndex(index) || !updatedNode) {
                    return;
                }

                nodes.value[index] = Object.assign(nodes.value[index], updatedNode);
                nodes.value = [...nodes.value];
            }

            function findNodeById(id) {
                if (id !== 0 && !id) {
                    return null;
                }

                const globalParent = {
                    id: "__globalid__",
                    expanded: false,
                    isLeaf: false,
                    level: -1,
                    children: nodes.value,
                };

                let result = null;

                walkNodeTree({value: [globalParent]}, 0, (node) => {
                    if (equalIds(id, node.id)) {
                        result = node;
                        return false;
                    }
                });

                return result;
            }

            function findParentByNodeId(id) {
                if (id !== 0 && !id) {
                    return null;
                }

                const globalParent = {
                    id: 0,
                    expanded: false,
                    isLeaf: false,
                    level: -1,
                    children: nodes.value,
                };

                const parents = [globalParent];
                let parent;

                while (parent = parents.pop()) {
                    const children = parent.children;

                    if (!children || !children.length) {
                        continue;
                    }

                    const localParents = [{
                        level: parent.level,
                        node: parent,
                    }];

                    let level = parent.level + 1;
                    let currentParent = parent;

                    const length = children.length;

                    for (let i = 0; i < length; i++) {
                        const node = children[i];

                        if (node.level > level) {
                            currentParent = children[i - 1];
                            level = currentParent.level + 1;

                            localParents.push({
                                level: currentParent.level,
                                node: currentParent,
                            });
                        }
                        else if (node.level < level) {
                            let foundParentIndex = -1;

                            for (let j = localParents.length - 1; j >= 0; j--) {
                                if (localParents[j].level === node.level - 1) {
                                    foundParentIndex = j;
                                    break;
                                }
                            }

                            if (foundParentIndex < 0) {
                                return null;
                            }

                            localParents.splice(foundParentIndex + 1);
                            const foundParent = localParents[foundParentIndex];

                            currentParent = foundParent.node;
                            level = foundParent.level + 1;
                        }

                        if (equalIds(id, node.id)) {
                            return {
                                parent: currentParent,
                                container: currentParent === globalParent ? null : children,
                                index: i,
                            };
                        }

                        if (!node.isLeaf && !node.extended) {
                            parents.push(node);
                        }
                    }
                }

                return null;
            }

            function getDirectChildren(parentIndex) {
                if (!checkNodesIndex(parentIndex)) {
                    return [];
                }

                const node = nodes.value[parentIndex];

                if (node.isLeaf) {
                    return [];
                }

                const childLevel = node.level + 1;

                let arr = node.children || [];
                let from = 0;
                let to = arr.length - 1;

                if (node.expanded) {
                    arr = nodes.value;
                    from = parentIndex + 1;
                    to = findLastChildIndex(arr, parentIndex);
                }

                if (!(arr && arr.length && to >= 0)) {
                    return [];
                }

                const result = [];

                for (let i = from; i <= to; i++) {
                    const child = arr[i];

                    if (child.level === childLevel) {
                        result.push(child);
                    }
                }

                return result;
            }

            function getSelectedId() {
                return selectedId.value;
            }

            function setSelected(index) {
                const node = getNode(index);
                selectedId.value = node ? node.id : null;
            }

            function scrollToIndex(index) {
                if (!checkNodesIndex(index)) {
                    return;
                }

                nextTick(() => scrollTo(index));
            }

            function scrollToId(realId) {
                nextTick(() => {
                    const index = findIndexById(realId);

                    if (index < 0) {
                        return;
                    }

                    scrollTo(index);
                });
            }

            function goToLine() {
                let line = parseInt(lineInput.value);

                line = Math.min(line, nodes.value.length);
                line = Math.max(line, 0);

                scrollToIndex(line);
            }

            function walkNodeTree(nodes, nodeStartIndex, fn) {
                if (!checkNodesIndex(nodeStartIndex) || !fn) {
                    return;
                }

                const parents = [];
                let currentParent;

                const lastChildIndex = findLastChildIndex(nodes.value, nodeStartIndex);

                let i = lastChildIndex < 0 ? nodeStartIndex : lastChildIndex;

                while (i >= nodeStartIndex) {
                    const node = nodes.value[i];

                    if (fn(node) === false) {
                        return;
                    }

                    if (!node.isLeaf && !node.expanded) {
                        parents.push(node);
                    }

                    i -= 1;
                }

                while (currentParent = parents.pop()) {
                    const children = currentParent.children;

                    if (!children || !children.length) {
                        continue;
                    }

                    i = children.length - 1;

                    while (i >= 0) {
                        const node = children[i];

                        if (fn(node) === false) {
                            return;
                        }

                        if (!node.isLeaf && !node.expanded) {
                            parents.push(node);
                        }

                        i -= 1;
                    }
                }
            }

            function getFlatTree(onlyIds = false) {
                const copyNode = (node) => {
                    const result = {};

                    for (const key in node) {
                        if (Object.hasOwnProperty.call(node, key)) {
                            if (key === "children") {
                                continue;
                            }

                            const value = node[key];

                            if (Array.isArray(value)) {
                                continue;
                            }

                            if (typeof value === "object" && value !== null) {
                                continue;
                            }

                            result[key] = value;
                        }
                    }

                    return result;
                }

                const globalParent = {
                    id: 0,
                    expanded: false,
                    isLeaf: false,
                    level: -1,
                    children: nodes.value,
                };

                const result = [];
                const parents = [globalParent];
                let parent;

                while (parent = parents.pop()) {
                    const children = parent.children;

                    if (!children || !children.length) {
                        continue;
                    }

                    const localParents = [{
                        level: parent.level,
                        id: parent.id,
                        order: 1,
                    }];

                    let level = parent.level + 1;
                    let parentId = parent.id;
                    let order = 1;

                    const length = children.length;

                    for (let i = 0; i < length; i++) {
                        const node = children[i];

                        if (node.level > level) {
                            localParents[localParents.length - 1].order = order;

                            const currentParent = children[i - 1];
                            level = currentParent.level + 1;
                            parentId = currentParent.id;
                            order = 1;

                            localParents.push({
                                level: currentParent.level,
                                id: currentParent.id,
                                order: 1,
                            });
                        }
                        else if (node.level < level) {
                            let foundParentIndex = -1;

                            for (let j = localParents.length - 1; j >= 0; j--) {
                                if (localParents[j].level === node.level - 1) {
                                    foundParentIndex = j;
                                    break;
                                }
                            }

                            if (foundParentIndex < 0) {
                                throw new Error("Critical error when building flat tree!");
                            }

                            localParents.splice(foundParentIndex + 1);
                            const foundParent = localParents[foundParentIndex];

                            level = foundParent.level + 1;
                            parentId = foundParent.id;
                            order = foundParent.order;
                        }

                        const insertNode = onlyIds ? { id: node.id } : copyNode(node);
                        insertNode.order = order;
                        insertNode.parent = parentId;
                        result.push(insertNode);

                        if (!node.isLeaf && !node.extended) {
                            parents.push(node);
                        }

                        order += 1;
                    }
                }

                return result;
            }

            function getLastIndex() {
                return nodes.value.length - 1;
            }

            function getLastRelativeIndex() {
                let rIndex = 0;

                for (let i = nodes.value.length - 1; i >= 0; i--) {
                    if (nodes.value[i].level === 0) {
                        rIndex += 1;
                    }
                }

                return rIndex;
            }

            function equalIds(id1, id2) {
                return `${id1}` === `${id2}`;
            }

            function newId() {
                const id = `${newIdCounter}${Math.floor(Math.random() * Date.now()).toString(36)}`;
                newIdCounter += 1;
                return id;
            }

            function updateTree() {
                nodes.value = [...nodes.value];
            }

            function shouldCancelStart(e) {
                return e.target.tagName === "BUTTON";
            }

            return {
                api,
                treeRef,
                nodes,
                slickList,
                lineInput,
                selectedId,
                treeStyles,
                containerProps,
                wrapperProps,
                sortStart,
                sortEnd,
                sortCancel,
                expandAll,
                collapseAll,
                toggleExpand,
                addItemHandler,
                removeItemHandler,
                selectHandler,
                goToLine,
                equalIds,
                shouldCancelStart,
            }
        },

        template: /*html*/`
            <div ref="treeRef" class="pp-tree" :style="treeStyles" :class="{ 'pp-tree-dark': $props.dark }">
                <slot name="top" :api="api">
                    <section class="pp-tree-top">
                        <div>
                            <button @click="expandAll">Expand all</button>
                            <button @click="collapseAll">Collapse all</button>
                        </div>
                        <div>
                            <label>
                                Go to line:
                                <input v-model="lineInput" />
                                <button @click="goToLine">Go</button>
                            </label>
                        </div>
                    </section>
                </slot>

                <section class="pp-tree-container" v-bind="containerProps">
                    <slick-list
                        class="pp-tree-slicklist"
                        axis="y"
                        :list="nodes"
                        @sort-start="sortStart"
                        @sort-end="sortEnd"
                        @sort-cancel="sortCancel"
                        :should-cancel-start="shouldCancelStart"
                        :helperClass="$props.dark ? 'pp-tree-helper pp-tree-dark' : 'pp-tree-helper'"
                        :hideSortableGhost="false"
                        useDragHandle
                        :pressDelay="50"
                        :transitionDuration="0"
                        v-bind="wrapperProps">

                        <slick-item
                            v-for="(item, i) in slickList"
                            :key="item.data.id"
                            :index="i"
                            :disabled="item.data.disableDrag"
                            @click="selectHandler"
                            class="pp-tree-item"
                            :class="{
                                'pp-tree-inactive': item.data.inactive,
                                'pp-tree-no-drag': item.data.disableDrag,
                                'pp-tree-selected': equalIds(item.data.id, selectedId),
                            }"
                            :data-id="item.data.id"
                            tabindex="0">

                            <span :style="{ marginLeft: \`\$\{item.data.level * 20\}px\` }"></span>

                            <button v-if="!item.data.isLeaf"
                                @click="toggleExpand(item.index)"
                                class="pp-tree-expand"
                                :class="{ 'pp-tree-expanded': !item.data.expanded }">
                            </button>

                            <drag-handle class="pp-tree-drag" :style="{ marginLeft: item.data.isLeaf ? '20px' : null }">
                            </drag-handle>

                            <slot name="item-title" :item="item.data" :index="item.index">
                                <span>{{ item.data.title }} (id: {{ item.data.id }})</span>
                            </slot>

                            <slot name="item-buttons" :item="item.data" :index="item.index">
                                <div class="pp-tree-buttons">
                                    <button @click="addItemHandler" :disabled="item.data.preventChildren">Add</button>
                                    <button @click="removeItemHandler">Delete</button>
                                </div>
                            </slot>

                        </slick-item>

                    </slick-list>
                </section>
            </div>
        `
    }

})(window);
